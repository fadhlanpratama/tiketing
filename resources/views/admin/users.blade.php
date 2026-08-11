@extends('admin.layout')

@section('title', 'Persetujuan Akun')
@section('page-title', 'Persetujuan Akun Pengguna')
@section('page-desc', 'Kelola verifikasi pendaftaran akun pengguna baru')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">
    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
        <h3 class="font-bold text-slate-800 text-base">Permohonan Registrasi Akun</h3>
        <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">
            {{ $pendingUsers->count() }} Permohonan
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-xs font-semibold border-b border-slate-100">
                    <th class="p-3.5">NAMA LENGKAP</th>
                    <th class="p-3.5">EMAIL</th>
                    <th class="p-3.5">NO TELP</th>
                    <th class="p-3.5">PILIH DIVISI</th>
                    <th class="p-3.5">PILIH ROLE</th>
                    <th class="p-3.5 text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="text-xs text-slate-600 divide-y divide-slate-100">
                @forelse($pendingUsers as $user)
                <tr>
                    <td class="p-3.5 font-bold text-slate-800">{{ $user->nama_lengkap }}</td>
                    <td class="p-3.5">{{ $user->email }}</td>
                    <td class="p-3.5">{{ $user->no_telp }}</td>
                    <form action="{{ route('admin.user.approve', $user->id) }}" method="POST">
                        @csrf
                        <td class="p-3.5">
                            <select name="divisi" required class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs focus:outline-none focus:border-amber-500">
                                <option value="" disabled selected>-- Pilih Divisi --</option>
                                @foreach($daftarDivisi as $div)
                                    <option value="{{ $div }}">{{ $div }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="p-3.5">
                            <select name="role" required class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs focus:outline-none focus:border-amber-500">
                                <option value="user" selected>User (Pegawai)</option>
                                <option value="pj">PJ (Teknisi)</option>
                            </select>
                        </td>
                        <td class="p-3.5 text-center space-x-1">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold px-3 py-1.5 rounded-lg transition">
                                Setujui
                            </button>
                    </form>
                    <form action="{{ route('admin.user.reject', $user->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Tolak pendaftaran ini?')" class="bg-red-500 hover:bg-red-600 text-white font-bold px-3 py-1.5 rounded-lg transition">
                            Tolak
                        </button>
                    </form>
                        </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-slate-400">Tidak ada permohonan pendaftaran akun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection