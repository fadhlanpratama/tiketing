<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - TIXPass ESDM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased flex flex-col">

    <header class="bg-[#0a2540] text-white sticky top-0 z-30 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-amber-400 p-2 rounded-xl text-[#0a2540]">
                    <i class="fa-solid fa-ticket text-lg"></i>
                </div>
                <div>
                    <h1 class="text-white font-black tracking-wider text-lg">TIXPass</h1>
                    <span class="text-[10px] text-amber-400 uppercase font-bold tracking-widest block -mt-1">Portal Pengguna</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-slate-300">Selamat datang,</p>
                    <p class="text-sm font-bold text-white">{{ session('user_logged') }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-400 flex items-center justify-center text-[#0a2540] font-bold uppercase shadow-inner">
                    {{ substr(session('user_logged'), 0, 2) }}
                </div>
                <div class="h-6 w-px bg-slate-700 hidden sm:block"></div>
                <a href="{{ route('logout') }}" class="flex items-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition">
                    <i class="fa-solid fa-right-from-bracket"></i> <span class="hidden sm:inline">Logout</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <div class="bg-gradient-to-r from-[#0a2540] to-[#16406c] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-5 pointer-events-none bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="relative z-10">
                <span class="bg-amber-400 text-[#0a2540] text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md mb-3 inline-block">Akses Karyawan</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Butuh Bantuan IT atau Fasilitas?</h2>
                <p class="text-slate-300 text-sm mt-1">Buat, pantau, dan kelola semua permintaan tiket internal Anda dengan mudah.</p>
            </div>
            <button class="bg-amber-400 hover:bg-amber-300 text-[#0a2540] font-bold text-sm px-6 py-3 rounded-xl shadow-lg transition transform active:scale-95 flex items-center gap-2 shrink-0 self-stretch sm:self-auto justify-center">
                <i class="fa-solid fa-plus"></i> Buat Tiket Baru
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tiket Aktif</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">3 Tiket</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dalam Proses</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">1 Tiket</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Selesai</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">14 Tiket</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Pengajuan Tiket</h3>
                    <p class="text-xs text-slate-400">Status pelacakan real-time untuk permohonan layanan internal Anda</p>
                </div>
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Cari nomor tiket..." class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-2 rounded-xl focus:outline-none focus:border-amber-500 transition text-sm">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-semibold text-xs tracking-wider border-b border-slate-100">
                            <th class="p-4 pl-6">ID TIKET</th>
                            <th class="p-4">KATEGORI PERMINTAAN</th>
                            <th class="p-4">TANGGAL PENGAJUAN</th>
                            <th class="p-4">STATUS</th>
                            <th class="p-4 pr-6 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 pl-6 font-semibold text-slate-700">#TX-00482</td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">Gangguan Jaringan Pusdatin</p>
                                <span class="text-xs text-slate-400">Koneksi internet lambat di Lantai 3</span>
                            </td>
                            <td class="p-4">02 Jul 2026</td>
                            <td class="p-4">
                                <span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Pending</span>
                            </td>
                            <td class="p-4 pr-6 text-center">
                                <button class="text-slate-600 hover:text-slate-800 font-semibold text-xs px-3 py-1.5 bg-slate-100 rounded-lg transition">Detail</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 pl-6 font-semibold text-slate-700">#TX-00431</td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">Permintaan Akun Aplikasi</p>
                                <span class="text-xs text-slate-400">Akses modul pelaporan database</span>
                            </td>
                            <td class="p-4">28 Jun 2026</td>
                            <td class="p-4">
                                <span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Diproses</span>
                            </td>
                            <td class="p-4 pr-6 text-center">
                                <button class="text-slate-600 hover:text-slate-800 font-semibold text-xs px-3 py-1.5 bg-slate-100 rounded-lg transition">Detail</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 pl-6 font-semibold text-slate-700">#TX-00390</td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">Perbaikan Hardware</p>
                                <span class="text-xs text-slate-400">Kabel proyektor ruang rapat utama rusak</span>
                            </td>
                            <td class="p-4">15 Jun 2026</td>
                            <td class="p-4">
                                <span class="bg-green-100 text-green-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Selesai</span>
                            </td>
                            <td class="p-4 pr-6 text-center">
                                <button class="text-slate-600 hover:text-slate-800 font-semibold text-xs px-3 py-1.5 bg-slate-100 rounded-lg transition">Detail</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>