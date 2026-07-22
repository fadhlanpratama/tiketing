<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Users;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:admin');
    }

    public function index(Request $request)
    {
        // 1. Data User Pending
        $pendingUsers = Users::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Data Master Seluruh Tiket
        $allTickets = Ticket::with('pelapor')
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Data Tiket Open tanpa PJ
        $unassignedTickets = Ticket::where('status', 'Open')
            ->where(function($q) {
                $q->whereNull('penanggung_jawab')->orWhere('penanggung_jawab', '');
            })
            ->with('pelapor')
            ->orderBy('created_at', 'asc')
            ->get();

        // 4. Data Tiket Status Resolved
        $resolvedTickets = Ticket::where('status', 'Resolved')
            ->with('pelapor')
            ->orderBy('updated_at', 'desc')
            ->get();

        // 5. Data PJ Aktif
        $activePjs = Users::where('role', 'pj')
            ->where('status', 'active')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        // 6. Daftar Divisi
        $daftarDivisi = [
            "IT", "Humas", "Perpustakaan", "Perencanaan", "Keuangan", 
            "Monitoring", "Kepegawaian", "Sarana Prasarana", 
            "Keamanan dan Kebersihan", "Pengadaan", "Kearsipan", "Angkutan"
        ];

        return view('admin.dashboard', compact(
            'pendingUsers',
            'allTickets',
            'unassignedTickets',
            'resolvedTickets',
            'activePjs',
            'daftarDivisi'
        ));
    }

    public function approveUser(Request $request, string $id)
    {
        $request->validate([
            'divisi' => 'required|string',
            'role'   => 'required|in:user,pj',
        ], [
            'divisi.required' => 'Divisi wajib dipilih.',
            'role.required'   => 'Role pengguna wajib ditentukan.',
        ]);

        $user = Users::where('id', $id)->where('status', 'pending')->firstOrFail();
        $user->divisi = $request->divisi;
        $user->role   = $request->role;
        $user->status = 'active';
        $user->save();

        return redirect()->back()->with('success', "Akun {$user->nama_lengkap} berhasil disetujui.");
    }

    public function rejectUser(string $id)
    {
        $user = Users::where('id', $id)->where('status', 'pending')->firstOrFail();
        
        $namaUser = $user->nama_lengkap;
        
        // Hapus permanen data dari tabel users
        $user->delete();

        return redirect()->back()->with('error', "Pendaftaran akun {$namaUser} telah ditolak dan dihapus.");
    }

    public function assignPJ(Request $request, string $id)
    {
        $request->validate([
            'penanggung_jawab' => 'required|string',
        ], [
            'penanggung_jawab.required' => 'Pilih PJ/Teknisi terlebih dahulu.',
        ]);

        $ticket = Ticket::where('id', $id)->firstOrFail();
        $ticket->penanggung_jawab = $request->penanggung_jawab;
        $ticket->save();

        return redirect()->back()->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' berhasil ditugaskan ke PJ: ' . $request->penanggung_jawab);
    }

    public function closeTicket(string $id)
    {
        $ticket = Ticket::where('id', $id)->where('status', 'Resolved')->firstOrFail();

        $ticket->status = 'Closed';
        $ticket->closed_by = 'admin';
        $ticket->save();

        return redirect()->back()->with('success', 'Tiket #' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT) . ' resmi ditutup (Closed).');
    }
}