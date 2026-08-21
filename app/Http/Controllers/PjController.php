<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Users;
use App\Models\TicketCollaborator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PjController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:pj');
    }

   private function pjTicketQuery(int $pjId, bool $includeCollab = true)
    {
        if (!$includeCollab) {
        return Ticket::where('pj_id', $pjId);
        }

        return Ticket::where(function ($q) use ($pjId) {
            $q->where('pj_id', $pjId)
            ->orWhereHas('collaborators', fn ($qq) => $qq->where('pj_id', $pjId));
        });
    }

    public function index(Request $request)
    {
        $pjId = session('user_id');

        $query = $this->pjTicketQuery($pjId)
            ->with('pelapor');

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

        if ($request->filled('prioritas') && $request->prioritas !== 'semua') {
            $query->whereRaw('LOWER(prioritas) = ?', [strtolower($request->prioritas)]);
        }

        $tickets = $query->orderBy('created_at', 'asc')->paginate(10)->withQueryString();
        $tickets->getCollection()->transform(function ($ticket) use ($pjId) {
            $ticket->is_collaborator = $ticket->pj_id != $pjId;
            return $ticket;
        });

        $countQuery = $this->pjTicketQuery($pjId, false);

        if ($request->filled('prioritas') && $request->prioritas !== 'semua') {
            $countQuery->whereRaw('LOWER(prioritas) = ?', [strtolower($request->prioritas)]);
        }

        $counts = $countQuery->selectRaw("
                COUNT(CASE WHEN status = 'Open' THEN 1 END) as menunggu,
                COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as diproses,
                COUNT(CASE WHEN status = 'Resolved' OR (status = 'Closed' AND (closed_by = 'admin' OR closed_by IS NULL)) THEN 1 END) as selesai,
                COUNT(CASE WHEN status = 'Closed' AND closed_by = 'user' THEN 1 END) as dibatalkan
            ")
            ->first();

        $overdueQuery = $this->pjTicketQuery($pjId, false)
            ->where(function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('closed_by', '!=', 'user')
                        ->orWhereNull('closed_by');
                });
            })
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
            'tickets'         => $tickets,
            'menunggu'        => $counts->menunggu ?? 0,
            'diproses'        => $counts->diproses ?? 0,
            'selesai'         => $counts->selesai ?? 0,
            'dibatalkan'      => $counts->dibatalkan ?? 0,
            'slaTerlambat'    => $slaTerlambat,
            'ticketsOverSla'  => $ticketsOverSla,
            'slaPercentage'   => $slaPercentage,
            'slaNeedleDeg'    => $slaNeedleDeg,
            'statusFilter'    => $request->input('status', 'semua'),
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
            Ticket::where('pj_id', $userId)
                ->update(['penanggung_jawab' => $namaBaru]);
        }

        session(['nama_lengkap' => $user->nama_lengkap]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function terima(string $id)
    {
        $pjId = session('user_id');
        $ticket = Ticket::where('id', $id)
            ->where('pj_id', $pjId)
            ->where('status', 'Open')
            ->firstOrFail();

        $waktuMulai = now();
        $updated = Ticket::where('id', $ticket->id)
            ->where('status', 'Open')
            ->update([
                'status' => 'In Progress',
                'waktu_mulai_dikerjakan' => $waktuMulai,
                'sla_target_menit' => Ticket::getSlaTargetMenitByPrioritas($ticket->prioritas),
                'sla_status' => 'Berjalan',
                'user_notif_inprogress_read' => false,
            ]);

        if (!$updated) {
            return back()->with('error', 'Tiket sudah diproses oleh pengguna lain.');
        }

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

        $pjId = session('user_id');
        $ticket = Ticket::where('id', $id)
            ->where('pj_id', $pjId)
            ->where('status', 'In Progress')
            ->firstOrFail();

        $path = $request->file('bukti_foto')->store('tickets_resolved', 'public');

        $tanggalSelesai = now();
        $updates = [
            'status' => 'Resolved',
            'tanggal_selesai' => $tanggalSelesai,
            'hasil_resolved_foto' => $path,
            'user_notif_resolved_read' => false,
        ];

        if ($ticket->waktu_mulai_dikerjakan && $ticket->sla_target_menit) {
            $deadline = $ticket->waktu_mulai_dikerjakan->copy()->addMinutes($ticket->sla_target_menit);

            if ($tanggalSelesai->greaterThan($deadline)) {
                $updates['sla_lebih_menit'] = $deadline->diffInMinutes($tanggalSelesai);
                $updates['sla_status'] = 'Terlambat';
            } else {
                $updates['sla_lebih_menit'] = 0;
                $updates['sla_status'] = 'Tepat Waktu';
            }
        }

        if ($request->filled('catatan_penyelesaian')) {
            $updates['deskripsi_masalah'] = $ticket->deskripsi_masalah . "\n\n[Catatan PJ - " . $tanggalSelesai->format('Y-m-d H:i') . "]: "
                . strip_tags($request->catatan_penyelesaian);
        }

        $updated = Ticket::where('id', $ticket->id)
            ->where('status', 'In Progress')
            ->update($updates);

        if (!$updated) {
            Storage::disk('public')->delete($path);

            return back()->with('error', 'Tiket sudah diselesaikan oleh pengguna lain.');
        }

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

        $pjId = session('user_id');
        $namaPj = session('nama_lengkap');
        $ticket = $this->pjTicketQuery($pjId)
            ->where('id', $id)
            ->where('status', 'In Progress')
            ->firstOrFail();

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('ticket_messages', 'public');
        }

        $message = $ticket->messages()->create([
            'sender_type'  => 'pj',
            'sender_nama'  => $namaPj,
            'pesan'        => $request->filled('pesan') ? strip_tags($request->pesan) : null,
            'foto'         => $path,
            'read_by_pj'   => $ticket->pj_id == $pjId,
            'read_by_user' => false,
        ]);

        $recipientIds = $ticket->collaborators()
            ->where('pj_id', '!=', $pjId)
            ->pluck('pj_id');

        if ($ticket->pj_id != $pjId) {
            $recipientIds->push($ticket->pj_id);
        }

        $message->recipients()->createMany(
            $recipientIds->unique()->map(fn ($userId) => [
                'user_id' => $userId,
                'read' => false,
            ])->values()->all()
        );

        return back()->with('success', 'Pesan terkirim.');
    }

    public function show(string $id)
    {
        $pjId = session('user_id');
        $ticket = $this->pjTicketQuery($pjId)
            ->where('id', $id)
            ->with(['pelapor', 'collaborators.pj'])
            ->firstOrFail();

        $isOwner = $ticket->pj_id == $pjId;
        if ($isOwner) {
            $ticket->messages()
                ->where('sender_type', '!=', 'pj')
                ->where('read_by_pj', false)
                ->update(['read_by_pj' => true]);
        }

        $ticket->messages()
            ->whereHas('recipients', fn ($query) => $query->where('user_id', $pjId)->where('read', false))
            ->with('recipients')
            ->get()
            ->each(function ($message) use ($pjId) {
                $message->recipients()
                    ->where('user_id', $pjId)
                    ->update(['read' => true]);
            });

        $needsSave = false;

        if ($isOwner) {
            if ($ticket->status === 'Closed' && $ticket->closed_by === 'user' && !$ticket->pj_notif_closed_read) {
                $ticket->pj_notif_closed_read = true;
                $needsSave = true;
            }

            if ($ticket->status === 'Closed' && $ticket->closed_by === 'admin' && !$ticket->pj_notif_admin_closed_read) {
                $ticket->pj_notif_admin_closed_read = true;
                $needsSave = true;
            }

            if (!$ticket->pj_notif_assigned_read) {
                $ticket->pj_notif_assigned_read = true;
                $needsSave = true;
            }
        } else {
            $collaborator = $ticket->collaborators()->where('pj_id', $pjId)->first();
            if ($collaborator && (!$collaborator->invitation_read || !$collaborator->closed_notif_read)) {
                $collaborator->invitation_read = true;
                if ($ticket->status === 'Closed' && $ticket->closed_by === 'user') {
                    $collaborator->closed_notif_read = true;
                }
                $collaborator->save();
            }
        }

        if ($needsSave) {
            $ticket->timestamps = false;
            $ticket->save();
        }

        $prioritas = strtolower($ticket->prioritas);
        $targetSlaText = match($prioritas) {
            'tinggi' => '1 Hari Kerja',
            'sedang' => '3 Hari Kerja',
            default  => '7 Hari Kerja',
        };

        $availablePjs = [];
        if ($isOwner) {
            $sudahDiundang = $ticket->collaborators->pluck('pj_id')->toArray();

            $availablePjs = Users::where('role', 'pj')
                ->where('status', 'active')
                ->where('id', '!=', $pjId)
                ->whereNotIn('id', $sudahDiundang)
                ->orderBy('nama_lengkap')
                ->get(['id', 'nama_lengkap', 'divisi']);
        }

        return view('pj.detail', compact('ticket', 'targetSlaText', 'isOwner', 'availablePjs'));
    }

    public function inviteCollaborator(Request $request, string $id)
    {
        $pjId = session('user_id');

        $ticket = Ticket::where('id', $id)
            ->where('pj_id', $pjId)
            ->firstOrFail();

        if (!in_array($ticket->status, ['Open', 'In Progress'])) {
            return back()->with('error', 'Kolaborator hanya dapat ditambahkan selama tiket berstatus Open atau In Progress.');
        }

        $request->validate([
            'collaborator_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'pj')->where('status', 'active');
                }),
            ],
        ], [
            'collaborator_id.required' => 'Pilih PJ yang ingin diundang.',
            'collaborator_id.exists'   => 'PJ yang dipilih tidak valid atau tidak aktif.',
        ]);

        $targetId = (int) $request->collaborator_id;

        if ($targetId === (int) $pjId) {
            return back()->with('error', 'Tidak bisa mengundang diri sendiri sebagai kolaborator.');
        }

        $target = Users::where('id', $targetId)
            ->where('role', 'pj')
            ->where('status', 'active')
            ->first();
        if (!$target) {
            return back()->with('error', 'PJ yang dipilih tidak valid.');
        }

        $sudahAda = TicketCollaborator::where('ticket_id', $ticket->id)
            ->where('pj_id', $targetId)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'PJ tersebut sudah menjadi kolaborator tiket ini.');
        }

        TicketCollaborator::create([
            'ticket_id'  => $ticket->id,
            'pj_id'      => $targetId,
            'invited_by' => $pjId,
        ]);

        return back()->with('success', $target->nama_lengkap . ' berhasil ditambahkan sebagai kolaborator.');
    }

    public function removeCollaborator(string $id, string $collabId)
    {
        $pjId = session('user_id');

        $ticket = Ticket::where('id', $id)
            ->where('pj_id', $pjId)
            ->firstOrFail();

        if (!in_array($ticket->status, ['Open', 'In Progress'])) {
            return back()->with('error', 'Kolaborator hanya dapat diubah selama tiket berstatus Open atau In Progress.');
        }

        $deleted = TicketCollaborator::where('id', $collabId)
            ->where('ticket_id', $ticket->id)
            ->delete();

        if (!$deleted) {
            return back()->with('error', 'Kolaborator tidak ditemukan.');
        }

        return back()->with('success', 'Kolaborator berhasil dihapus dari tiket ini.');
    }
}