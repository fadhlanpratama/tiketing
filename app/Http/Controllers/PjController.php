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

        $tickets = $query->orderBy('created_at', 'asc')->paginate(10)->withQueryString();

        $counts = Ticket::whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->selectRaw("
                COUNT(CASE WHEN status = 'Open' THEN 1 END) as menunggu,
                COUNT(CASE WHEN status = 'In Progress' THEN 1 END) as diproses,
                COUNT(CASE WHEN status IN ('Resolved', 'Closed') THEN 1 END) as selesai
            ")
            ->first();

        return view('pj.dashboard', [
            'tickets'      => $tickets,
            'menunggu'     => $counts->menunggu ?? 0,
            'diproses'     => $counts->diproses ?? 0,
            'selesai'      => $counts->selesai ?? 0,
            'statusFilter' => $request->input('status', 'semua'),
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
            'email'        => 'required|email:rfc,dns|max:254|unique:users,email,' . $userId,
            'no_telp'      => ['required', 'regex:/^[0-9+\-\s()]{8,20}$/'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()->mixedCase()];
        }

        $request->validate($rules, [
            'email.unique'       => 'Email sudah terdaftar. Silakan gunakan email lain.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'no_telp.regex'      => 'Format nomor telepon/WhatsApp tidak valid.',
        ]);

        $namaBaru = strip_tags($request->nama_lengkap);

        $user->nama_lengkap = $namaBaru;
        $user->email        = strip_tags($request->email);
        $user->no_telp      = strip_tags($request->no_telp);

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
            'pesan' => 'required|string|max:1000',
        ]);

        $namaPj = session('nama_lengkap');
        $ticket = Ticket::where('id', $id)
            ->whereRaw('LOWER(penanggung_jawab) = ?', [strtolower($namaPj)])
            ->where('status', 'In Progress')
            ->firstOrFail();

        $ticket->messages()->create([
            'sender_type' => 'pj',
            'sender_nama' => $namaPj,
            'pesan'       => strip_tags($request->pesan),
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

        return view('pj.detail', compact('ticket'));
    }
}