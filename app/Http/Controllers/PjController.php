<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Users;
use Illuminate\Validation\Rules\Password;

class PjController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:pj');
    }

    public function index(Request $request)
    {
        $namaPj = session('nama_lengkap');

        $query = Ticket::whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->with('pelapor');

        if ($request->filled('status') && $request->status !== 'semua') {
            $statusList = explode(',', $request->status);
            $query->whereIn('status', $statusList);
        }

        if ($request->filled('prioritas') && $request->prioritas !== 'semua') {
            $query->whereRaw('LOWER(prioritas) = ?', [strtolower($request->prioritas)]);
        }

        $tickets = $query->orderBy('created_at', 'asc')->paginate(10)->withQueryString();

        $counts = Ticket::whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->selectRaw("
                COUNT(CASE WHEN status = 'Open' THEN 1 END) as menunggu,
                COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as diproses,
                COUNT(CASE WHEN status IN ('Resolved', 'Closed') THEN 1 END) as selesai
            ")
            ->first();

        $overdueQuery = Ticket::whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->where(function ($q) {
                $q->where(function ($qq) {
                    $qq->where('status', 'In Progress')
                        ->whereNotNull('waktu_mulai_dikerjakan')
                        ->whereNotNull('sla_target_menit')
                        ->whereRaw('TIMESTAMPADD(MINUTE, sla_target_menit, waktu_mulai_dikerjakan) < NOW()');
                })->orWhere('sla_status', 'Terlambat');
            });

        $slaTerlambat = (clone $overdueQuery)->count();

        $ticketsOverSla = (clone $overdueQuery)
            ->orderByDesc('id')
            ->limit(20)
            ->get()
            ->sortByDesc(fn ($t) => $t->sla_lebih_menit_live)
            ->values();

        $totalTerpantauSla = ($counts->diproses ?? 0) + ($counts->selesai ?? 0);
        $slaPercentage = $totalTerpantauSla > 0
            ? min(100, round(($slaTerlambat / $totalTerpantauSla) * 100))
            : 0;
        $slaNeedleDeg = -90 + ($slaPercentage / 100) * 180;

        return view('pj.dashboard', [
            'tickets'        => $tickets,
            'menunggu'       => $counts->menunggu ?? 0,
            'diproses'       => $counts->diproses ?? 0,
            'selesai'        => $counts->selesai ?? 0,
            'slaTerlambat'   => $slaTerlambat,
            'ticketsOverSla' => $ticketsOverSla,
            'slaPercentage'  => $slaPercentage,
            'slaNeedleDeg'   => $slaNeedleDeg,
            'statusFilter'   => $request->input('status', 'semua'),
            'prioritasFilter' => $request->input('prioritas', 'semua'),
        ]);
    }

    public function editProfile()
    {
        $userId = session('user_id');
        $user = Users::findOrFail($userId);

        return view('pj.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $userId = session('user_id');
        $user = Users::findOrFail($userId);
        $namaLama = $user->nama_lengkap;

        $rules = [
            'nama_lengkap' => ['required', 'string', 'min:3', 'max:150'],

            'email' => [
                'required',
                'max:254',
                'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
                'unique:users,email,' . $userId,
            ],

            'no_telp' => [
                'required',
                'regex:/^[0-9+\-\s()]{8,20}$/',
            ],
        ];

        if ($request->filled('password')) {
            $rules['password'] = [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase(),
            ];
        }

        $request->validate($rules, [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.min'      => 'Nama lengkap minimal 3 karakter.',
            'email.required' => 'Masukkan alamat email.',
            'email.regex'    => 'Sertakan "@" dan domain yang valid pada alamat email (contoh: nama@domain.com).',
            'email.unique'   => 'Email ini sudah digunakan oleh akun lain.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'no_telp.regex'    => 'Format nomor telepon tidak valid! Gunakan angka 8-20 digit.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $namaBaru = strip_tags($request->nama_lengkap);
        $user->nama_lengkap = $namaBaru;
        $user->email = strip_tags($request->email);
        $user->no_telp = strip_tags($request->no_telp);

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        if ($namaLama !== $namaBaru) {
            Ticket::whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaLama)])
                ->update(['penanggung_jawab' => $namaBaru]);
        }

        session(['nama_lengkap' => $user->nama_lengkap]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

   public function terima(string $id)
    {
        $namaPj = session('nama_lengkap');
        $ticket = Ticket::where('id', $id)
            ->whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->where('status', 'Open')
            ->firstOrFail();

        $ticket->status = 'In Progress';
        $ticket->waktu_mulai_dikerjakan = now();
        $ticket->sla_target_menit = Ticket::getSlaTargetMenitByPrioritas($ticket->prioritas);
        $ticket->sla_status = 'Berjalan';
        $ticket->user_notif_inprogress_read = false;
        $ticket->timestamps = false;
        $ticket->save();

        return redirect()->route('pj.dashboard')
            ->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' mulai dikerjakan.');
    }

    public function selesaikan(Request $request, string $id)
    {
        $request->validate([
            'catatan_penyelesaian' => 'nullable|string|max:1000',
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_foto.required' => 'Foto bukti penyelesaian wajib diunggah sesuai SOP.',
        ]);

        $namaPj = session('nama_lengkap');
        $ticket = Ticket::where('id', $id)
            ->whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->where('status', 'In Progress')
            ->firstOrFail();

        $path = $request->file('bukti_foto')->store('tickets_resolved', 'public');

        $ticket->status = 'Resolved';
        $ticket->tanggal_selesai = now();
        $ticket->hasil_resolved_foto = $path;
        $ticket->user_notif_resolved_read = false;

        if ($ticket->waktu_mulai_dikerjakan && $ticket->sla_target_menit) {
            $deadline = $ticket->waktu_mulai_dikerjakan->copy()->addMinutes($ticket->sla_target_menit);

            if ($ticket->tanggal_selesai->greaterThan($deadline)) {
                $ticket->sla_lebih_menit = $deadline->diffInMinutes($ticket->tanggal_selesai);
                $ticket->sla_status = 'Terlambat';
            } else {
                $ticket->sla_lebih_menit = 0;
                $ticket->sla_status = 'Tepat Waktu';
            }
        }

        if ($request->filled('catatan_penyelesaian')) {
            $ticket->deskripsi_masalah .= "\n\n[Catatan PJ - " . now()->format('Y-m-d H:i') . "]: "
                . strip_tags($request->catatan_penyelesaian);
        }

        $ticket->timestamps = false;
        $ticket->save();

        return redirect()->route('pj.dashboard')
            ->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' berhasil diselesaikan.');
    }

    public function storeMessage(Request $request, string $id)
    {
        $request->validate([
            'pesan' => 'nullable|string|max:1000|required_without:foto',
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'pesan.required_without' => 'Tulis pesan atau lampirkan foto.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'foto.max'   => 'Ukuran foto maksimal 2MB.',
        ]);

        $namaPj = session('nama_lengkap');
        $ticket = Ticket::where('id', $id)
            ->whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->where('status', 'In Progress')
            ->firstOrFail();

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('ticket_messages', 'public');
        }

        $ticket->messages()->create([
            'sender_type'  => 'pj',
            'sender_nama'  => $namaPj,
            'pesan'        => $request->filled('pesan') ? strip_tags($request->pesan) : null,
            'foto'         => $path,
            'read_by_pj'   => true,
            'read_by_user' => false,
        ]);

        return back()->with('success', 'Pesan terkirim.');
    }

    public function show(string $id)
    {
        $namaPj = session('nama_lengkap');

        $ticket = Ticket::where('id', $id)
            ->whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->with('pelapor')
            ->firstOrFail();

        // Tandai pesan dari pelapor sebagai sudah dibaca oleh PJ
        $ticket->messages()
            ->where('sender_type', '!=', 'pj')
            ->where('read_by_pj', false)
            ->update(['read_by_pj' => true]);

        $needsSave = false;

        // Notif: tiket dibatalkan pelapor
        if ($ticket->status === 'Closed' && $ticket->closed_by === 'user' && !$ticket->pj_notif_closed_read) {
            $ticket->pj_notif_closed_read = true;
            $needsSave = true;
        }

        // Notif: tiket ditutup admin
        if ($ticket->status === 'Closed' && $ticket->closed_by === 'admin' && !$ticket->pj_notif_admin_closed_read) {
            $ticket->pj_notif_admin_closed_read = true;
            $needsSave = true;
        }

        // Notif: penugasan baru dari admin
        if (!$ticket->pj_notif_assigned_read) {
            $ticket->pj_notif_assigned_read = true;
            $needsSave = true;
        }

        if ($needsSave) {
            $ticket->timestamps = false;
            $ticket->save();
        }

        return view('pj.detail', compact('ticket'));
    }
}