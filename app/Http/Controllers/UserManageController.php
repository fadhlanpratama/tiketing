<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use App\Models\Users;
use App\Models\Ticket;
use Illuminate\Validation\Rule;

class UserManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:admin');
    }

    private function daftarDivisi()
    {
        return [
            "IT", "Humas", "Perpustakaan", "Perencanaan", "Keuangan",
            "Monitoring", "Kepegawaian", "Sarana Prasarana",
            "Keamanan dan Kebersihan", "Pengadaan", "Kearsipan", "Angkutan"
        ];
    }

    public function index()
    {
        $pendingUsers = Users::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users', [
            'pendingUsers' => $pendingUsers,
            'daftarDivisi' => $this->daftarDivisi(),
        ]);
    }

    public function approve(Request $request, string $id)
    {
        $request->validate([
            'divisi' => ['required', Rule::in($this->daftarDivisi())],
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

    public function manage(Request $request)
    {
        $query = Users::where('status', '!=', 'pending');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('nama_lengkap', 'asc')->get();

        return view('admin.users_manage', [
            'users'        => $users,
            'daftarDivisi' => $this->daftarDivisi(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_lengkap' => ['required', 'string', 'min:3', 'max:150'],
                'email'        => ['required', 'email:rfc,dns', 'max:254', 'unique:users,email'],
                'no_telp'      => ['required', 'regex:/^[0-9+\-\s()]{8,20}$/'],
                'password'     => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()->mixedCase()],
                'divisi'       => ['required', Rule::in($this->daftarDivisi())],
                'role'         => ['required', 'in:user,pj,admin'],
            ], [
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'nama_lengkap.min'      => 'Nama lengkap minimal 3 karakter.',
                'email.required'        => 'Email wajib diisi.',
                'email.email'           => 'Format email tidak valid.',
                'email.unique'          => 'Email sudah terdaftar. Silakan gunakan email lain.',
                'no_telp.required'      => 'Nomor telepon wajib diisi.',
                'no_telp.regex'         => 'Format nomor telepon tidak valid.',
                'password.required'     => 'Password wajib diisi.',
                'password.confirmed'    => 'Konfirmasi password tidak cocok.',
                'divisi.required'       => 'Divisi wajib dipilih.',
                'role.required'         => 'Role wajib dipilih.',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('open_modal', 'add');
        }

        Users::create([
            'nama_lengkap' => strip_tags($request->nama_lengkap),
            'email'        => strip_tags($request->email),
            'no_telp'      => strip_tags($request->no_telp),
            'password'     => bcrypt($request->password),
            'divisi'       => $request->divisi,
            'role'         => $request->role,
            'status'       => 'active',
        ]);

        return redirect()->route('admin.users.manage')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function create()
    {
        return view('admin.users_create', [
            'daftarDivisi' => $this->daftarDivisi(),
        ]);
    }

    public function edit(string $id)
    {
        $user = Users::findOrFail($id);

        return view('admin.users_edit', [
            'user'         => $user,
            'daftarDivisi' => $this->daftarDivisi(),
        ]);
    }
    
    public function update(Request $request, string $id)
    {
        $user = Users::findOrFail($id);
        $namaLama = $user->nama_lengkap;

        $rules = [
            'nama_lengkap' => ['required', 'string', 'min:3', 'max:150'],
            'email'        => ['required', 'max:254', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/', 'unique:users,email,' . $id],
            'no_telp'      => ['required', 'regex:/^[0-9+\-\s()]{8,20}$/'],
            'divisi'       => ['required', Rule::in($this->daftarDivisi())],
            'role'         => ['required', 'in:user,pj,admin'],
            'status'       => ['required', 'in:active,rejected'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)->letters()->numbers()->mixedCase()];
        }

        try {
            $request->validate($rules, [
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'email.required'        => 'Email wajib diisi.',
                'email.regex'           => 'Format email tidak valid.',
                'email.unique'          => 'Email sudah digunakan oleh akun lain.',
                'no_telp.required'      => 'Nomor telepon wajib diisi.',
                'no_telp.regex'         => 'Format nomor telepon tidak valid.',
                'divisi.required'       => 'Divisi wajib dipilih.',
                'role.required'         => 'Role wajib dipilih.',
                'status.required'       => 'Status wajib dipilih.',
                'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('open_modal', 'edit-' . $id);
        }

        $user->nama_lengkap = strip_tags($request->nama_lengkap);
        $user->email        = strip_tags($request->email);
        $user->no_telp       = strip_tags($request->no_telp);
        $user->divisi        = $request->divisi;
        $user->role           = $request->role;
        $user->status         = $request->status;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        if ($namaLama !== $user->nama_lengkap) {
            Ticket::where('pj_id', $id)->update(['penanggung_jawab' => $user->nama_lengkap]);
        }

        return redirect()->route('admin.users.manage')
            ->with('success', "Data {$user->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy(string $id)
    {
        $user = Users::findOrFail($id);

        if ((string) $user->id === (string) session('user_id')) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $namaUser = $user->nama_lengkap;
        $user->delete();

        return redirect()->route('admin.users.manage')
            ->with('success', "Akun {$namaUser} berhasil dihapus.");
    }

}