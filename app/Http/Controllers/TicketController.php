<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Users;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login');
    }

    private function getTiketBelumSurvei(Int $userId)
    {
        return Ticket::where('user_id', $userId)
            ->where('status', 'Closed')
            ->where('closed_by', 'admin')
            ->whereNull('survei_kepuasan')
            ->orderBy('created_at', 'asc')
            ->pluck('id');
    }

    public function index(Request $request)
    {
        $userId = session('user_id');

        $query = Ticket::where('user_id', $userId)->with('pelapor');

        if ($request->filled('status') && $request->status !== 'semua') {
            if ($request->status === 'Dibatalkan') {
                $query->where('status', 'Closed')->where('closed_by', 'user');
            } elseif ($request->status === 'Selesai') {
                $query->where(function ($q) {
                    $q->where('status', 'Resolved')
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'Closed')
                            ->where(function ($c) {
                                $c->where('closed_by', 'admin')
                                    ->orWhereNull('closed_by');
                            });
                    });
                });
            } else {
                $statusList = explode(',', $request->status);
                $query->whereIn('status', $statusList);
            }
        }

        $tickets = $query->orderBy('created_at', 'asc')->paginate(10)->withQueryString();

        $counts = Ticket::where('user_id', $userId)->selectRaw("
            COUNT(CASE WHEN status = 'Open' THEN 1 END) as aktif,
            COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as proses,
            COUNT(CASE WHEN status = 'Resolved' OR (status = 'Closed' AND (closed_by = 'admin' OR closed_by IS NULL)) THEN 1 END) as selesai,
            COUNT(CASE WHEN status = 'Closed' AND closed_by = 'user' THEN 1 END) as dibatalkan
        ")->first();

        $tiketBelumSurvei = $this->getTiketBelumSurvei($userId);

        return view('user.dashboard', [
            'tickets'          => $tickets,
            'TiketAktif'       => $counts->aktif ?? 0,
            'dalamProses'      => $counts->proses ?? 0,
            'selesai'          => $counts->selesai ?? 0,
            'dibatalkan'       => $counts->dibatalkan ?? 0,
            'statusFilter'     => $request->input('status', 'semua'),
            'tiketBelumSurvei' => $tiketBelumSurvei,
        ]);
    }

   public function editProfile()
    {
        $userId = session('user_id');
        $user = Users::findOrFail($userId);

        return view('user.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $userId = session('user_id');
        $user = Users::findOrFail($userId);

        $rules = [
            'nama_lengkap' => ['required', 'string', 'min:3', 'max:150'],
            'email'        => ['required', 'email:rfc,dns', 'max:254', 'unique:users,email,' . $userId],
            'no_telp'      => ['required', 'regex:/^[0-9+\-\s()]{8,20}$/'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase()
            ];
        }

        $request->validate($rules, [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.min'      => 'Nama lengkap minimal 3 karakter.',
            'nama_lengkap.max'      => 'Nama lengkap maksimal 150 karakter.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format alamat email tidak valid!',
            'email.unique'          => 'Email sudah terdaftar. Silakan gunakan email lain.',
            'no_telp.required'      => 'Nomor telepon wajib diisi.',
            'no_telp.regex'         => 'Nomor telepon tidak valid!',
            'password.required'     => 'Kata sandi baru wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user->nama_lengkap = strip_tags($request->nama_lengkap);
        $user->email        = strip_tags($request->email);
        $user->no_telp      = strip_tags($request->no_telp);

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        session(['nama_lengkap' => $user->nama_lengkap]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function create()
    {
        $userId = session('user_id');
        $user = Users::find($userId);
        $tiketBelumSurvei = $this->getTiketBelumSurvei($userId);

        if ($tiketBelumSurvei->isNotEmpty()) {
            return redirect()->route('user.dashboard')
                ->with('survei_pending', $tiketBelumSurvei->toArray());
        }

        $counts = Ticket::where('user_id', $userId)->selectRaw("
            COUNT(CASE WHEN status = 'Open' THEN 1 END) as aktif,
            COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as proses,
            COUNT(CASE WHEN status IN ('Resolved', 'Closed') THEN 1 END) as selesai
        ")->first();

        return view('user.create', [
            'user'        => $user,
            'TiketAktif'  => $counts->aktif ?? 0,
            'dalamProses' => $counts->proses ?? 0,
            'selesai'     => $counts->selesai ?? 0
        ]);
    }

    public function store(Request $request)
    {
        $userId = session('user_id');
        $tiketBelumSurvei = $this->getTiketBelumSurvei($userId);

        if ($tiketBelumSurvei->isNotEmpty()) {
            return redirect()->route('user.dashboard')
                ->with('survei_pending', $tiketBelumSurvei->toArray());
        }

        $request->validate([
            'kategori'             => 'required|in:IT—Software,IT—Hardware,IT—Jaringan,Administrasi,Sarana—Prasarana,Keamanan,Kebersihan,Lainnya',
            'sub_kategori'         => 'required|string|max:100',
            'sub_kategori_manual'  => 'required_if:sub_kategori,Lainnya|nullable|string|max:100',
            'deskripsi_masalah'    => 'required|string|max:2000',
            'nomor_bmn'            => 'nullable|string|max:30',
            'prioritas'            => 'required|in:Rendah,Sedang,Tinggi',
            'attachment_foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $ticket = new Ticket();
        $ticket->user_id           = session('user_id');
        $ticket->kategori          = $request->kategori;
        $ticket->deskripsi_masalah = strip_tags($request->deskripsi_masalah);
        $ticket->prioritas         = $request->prioritas;
        $ticket->status            = 'Open';

        if ($request->sub_kategori === 'Lainnya') {
            $ticket->sub_kategori  = strip_tags($request->sub_kategori_manual);
        } else {
            $ticket->sub_kategori  = $request->sub_kategori;
        }

        $ticket->nomor_bmn = $request->filled('nomor_bmn') ? strip_tags($request->nomor_bmn) : 'Non-BMN';
        $ticket->admin_notif_new_ticket_read = false;

        if ($request->hasFile('attachment_foto')) {
            $ticket->attachment_foto = $request->file('attachment_foto')->store('tickets_attachment', 'public');
        }

        $ticket->save();

        return redirect()->route('user.dashboard')->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' berhasil dibuat!');
    }

    public function show(string $id)
    {
        $userId = session('user_id');
        $ticket = Ticket::where('id', $id)->where('user_id', $userId)->with(['pj', 'collaborators.pj'])->firstOrFail();

        // Tandai pesan dari PJ sebagai sudah dibaca oleh user
        $ticket->messages()
            ->where('sender_type', 'pj')
            ->where('read_by_user', false)
            ->update(['read_by_user' => true]);

        $needsSave = false;

        // Notif: tiket Resolved oleh PJ
        if ($ticket->status === 'Resolved' && !$ticket->user_notif_resolved_read) {
            $ticket->user_notif_resolved_read = true;
            $needsSave = true;
        }

        // Notif: PJ sudah ditentukan admin
        if (!$ticket->user_notif_assigned_read) {
            $ticket->user_notif_assigned_read = true;
            $needsSave = true;
        }

        // Notif: tiket mulai dikerjakan (In Progress)
        if ($ticket->status === 'In Progress' && !$ticket->user_notif_inprogress_read) {
            $ticket->user_notif_inprogress_read = true;
            $needsSave = true;
        }

        // Notif: tiket ditutup admin
        if ($ticket->status === 'Closed' && $ticket->closed_by === 'admin' && !$ticket->user_notif_admin_closed_read) {
            $ticket->user_notif_admin_closed_read = true;
            $needsSave = true;
        }

        if ($needsSave) {
            $ticket->timestamps = false;
            $ticket->save();
        }

        return view('user.detail', compact('ticket'));
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

        $userId = session('user_id');
        $ticket = Ticket::where('id', $id)->where('user_id', $userId)->firstOrFail();

        if ($ticket->status !== 'In Progress') {
            return back()->with('error', 'Chat hanya tersedia saat tiket berstatus In Progress.');
        }

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('ticket_messages', 'public');
        }

        $ticket->messages()->create([
            'sender_type'  => 'user',
            'sender_nama'  => $ticket->pelapor->nama_lengkap ?? session('nama_lengkap', 'Pelapor'),
            'pesan'        => $request->filled('pesan') ? strip_tags($request->pesan) : null,
            'foto'         => $path,
            'read_by_pj'   => false,
            'read_by_user' => true,
        ]);

        return back()->with('success', 'Pesan terkirim.');
    }

    public function storeSurvey(Request $request, string $id)
    {
        $request->validate([
            'survei_kepuasan' => 'required|in:Tidak Puas,Kurang puas,Cukup,Puas,Sangat Puas',
        ]);

        $userId = session('user_id');
        $ticket = Ticket::where('id', $id)->where('user_id', $userId)->first();

        if (!$ticket) {
            return back()->with('error', 'Tiket tidak ditemukan.');
        }

        $bisaSurvei = $ticket->status === 'Resolved' 
            || ($ticket->status === 'Closed' && $ticket->closed_by !== 'user');

        if (!$bisaSurvei) {
            return back()->with('error', 'Tiket ini belum dapat dinilai.');
        }

        if ($ticket->survei_kepuasan !== null) {
            return back()->with('error', 'Anda sudah memberikan penilaian untuk tiket ini.');
        }

        $ticket->survei_kepuasan = $request->survei_kepuasan;
        $ticket->timestamps = false;
        $ticket->save();
        $ticket->messages()->create([
            'sender_type'  => 'user',
            'sender_nama'  => $ticket->pelapor->nama_lengkap ?? session('nama_lengkap', 'Pelapor'),
            'pesan'        => 'telah mengisi survei kepuasan: ' . $request->survei_kepuasan,
            'foto'         => null,
            'read_by_pj'   => false,
            'read_by_user' => true,
        ]);

        return back()->with('success', 'Terima kasih atas penilaian Anda!');
    }

   public function destroy(Request $request, string $id)
    {
        $request->validate([
            'alasan_tutup' => 'required|string|max:1000',
        ], [
            'alasan_tutup.required' => 'Alasan penutupan tiket wajib diisi.',
        ]);

        $userId = session('user_id');
        $ticket = Ticket::where('id', $id)
            ->where('user_id', $userId)
            ->whereIn('status', ['Open', 'In Progress'])
            ->firstOrFail();

        $namaUser = session('nama_lengkap', 'Pelapor');
        $tanggal  = now()->format('d-m-Y H:i');
        $alasan   = strip_tags($request->alasan_tutup);

        $log = "\n\n--- Ditutup oleh Pelapor ---\n"
            . "Nama    : {$namaUser}\n"
            . "Tanggal : {$tanggal} WIB\n"
            . "Alasan  : {$alasan}";

        $ticket->deskripsi_masalah = $ticket->deskripsi_masalah . $log;
        $ticket->status     = 'Closed';
        $ticket->closed_by  = 'user';
        $ticket->tanggal_selesai = now();
        $ticket->pj_notif_closed_read = false;
        $ticket->admin_notif_user_closed_read = false;
        $ticket->save();

        return redirect()->route('user.dashboard')->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' berhasil dibatalkan.');
    }
}