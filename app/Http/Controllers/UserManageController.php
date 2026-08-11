<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Users;

class UserManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:admin');
    }

    public function index()
    {
        $pendingUsers = Users::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $daftarDivisi = [
            "IT", "Humas", "Perpustakaan", "Perencanaan", "Keuangan",
            "Monitoring", "Kepegawaian", "Sarana Prasarana",
            "Keamanan dan Kebersihan", "Pengadaan", "Kearsipan", "Angkutan"
        ];

        return view('admin.users', compact('pendingUsers', 'daftarDivisi'));
    }

    public function approve(Request $request, string $id)
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

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->nama_lengkap} berhasil disetujui.");
    }

    public function reject(string $id)
    {
        $user = Users::where('id', $id)->where('status', 'pending')->firstOrFail();
        $namaUser = $user->nama_lengkap;
        $user->status = 'rejected';
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('error', "Pendaftaran akun {$namaUser} telah ditolak dan dihapus.");
    }
}