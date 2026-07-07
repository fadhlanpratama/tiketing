<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - TIXPass ESDM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased flex">

    <aside class="fixed inset-y-0 left-0 bg-[#0a2540] text-slate-300 w-64 hidden md:flex flex-col z-30 transition-all duration-300">
        <div class="h-20 flex items-center px-6 border-b border-slate-700/50 gap-3">
            <div class="bg-amber-400 p-2 rounded-xl text-[#0a2540]">
                <i class="fa-solid fa-ticket text-lg"></i>
            </div>
            <div>
                <h1 class="text-white font-black tracking-wider text-lg">TIXPass</h1>
                <span class="text-[10px] text-amber-400 uppercase font-bold tracking-widest">Internal ESDM</span>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 bg-slate-800 text-white px-4 py-3 rounded-xl transition text-sm font-medium">
                <i class="fa-solid fa-chart-pie text-amber-400 w-5"></i> Dashboard Overview
            </a>
            <a href="#" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-xl transition text-sm font-medium text-slate-400">
                <i class="fa-solid fa-clipboard-list w-5"></i> Kelola Tiket
            </a>
            <a href="#" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-xl transition text-sm font-medium text-slate-400">
                <i class="fa-solid fa-users w-5"></i> Data Pengguna
            </a>
            <a href="#" class="flex items-center gap-3 hover:bg-slate-800 hover:text-white px-4 py-3 rounded-xl transition text-sm font-medium text-slate-400">
                <i class="fa-solid fa-gears w-5"></i> Pengaturan Sistem
            </a>
        </nav>

        <div class="p-4 border-t border-slate-700/50 bg-[#071d33]">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-400 flex items-center justify-center text-[#0a2540] font-bold uppercase">
                    {{ substr(session('user_logged'), 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-white truncate">{{ session('user_logged') }}</p>
                    <span class="text-[11px] bg-amber-400/20 text-amber-400 px-2 py-0.5 rounded-md uppercase font-bold">{{ session('user_role') }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white w-full py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Keluar Aplikasi
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 md:pl-64 flex flex-col min-h-screen">
        
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-slate-600 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Dashboard Overview</h2>
                    <p class="text-xs text-slate-400 hidden sm:block">Sistem Informasi Manajemen Tiket Layanan Internal</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-slate-400 font-medium">Hari ini</p>
                    <p class="text-sm font-semibold text-slate-700">{{ date('d M Y') }}</p>
                </div>
                <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                        <i class="fa-regular fa-bell"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 space-y-6">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Tiket</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">1,248</h3>
                        <span class="text-xs text-green-500 font-medium"><i class="fa-solid fa-arrow-trend-up"></i> +12% minggu ini</span>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 text-xl">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tiket Pending</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">42</h3>
                        <span class="text-xs text-amber-500 font-medium"><i class="fa-regular fa-clock"></i> Butuh respon</span>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 text-xl">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tiket Selesai</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">1,186</h3>
                        <span class="text-xs text-green-500 font-medium"><i class="fa-regular fa-circle-check"></i> Rasio 95%</span>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-500 text-xl">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total User</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-1">312</h3>
                        <span class="text-xs text-slate-400 font-medium">Pegawai Terdaftar</span>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 text-xl">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Tiket Masuk Terbaru</h3>
                        <p class="text-xs text-slate-400">Daftar permohonan tiket internal esdm yang perlu ditinjau</p>
                    </div>
                    <button class="bg-[#0a2540] hover:bg-slate-800 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition self-start sm:self-center shadow-md">
                        Lihat Semua Tiket
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 font-semibold text-xs tracking-wider border-b border-slate-100">
                                <th class="p-4 pl-6">ID TIKET</th>
                                <th class="p-4">PEMOHON</th>
                                <th class="p-4">KATEGORI LAYANAN</th>
                                <th class="p-4">TANGGAL MASUK</th>
                                <th class="p-4">STATUS</th>
                                <th class="p-4 pr-6 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 pl-6 font-semibold text-slate-700">#TX-00482</td>
                                <td class="p-4">Ahmad Fauzi</td>
                                <td class="p-4">Gangguan Jaringan Pusdatin</td>
                                <td class="p-4">02 Jul 2026</td>
                                <td class="p-4">
                                    <span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Pending</span>
                                </td>
                                <td class="p-4 pr-6 text-center">
                                    <button class="text-blue-500 hover:text-blue-700 font-semibold text-xs px-3 py-1.5 bg-blue-50 rounded-lg transition">Proses</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 pl-6 font-semibold text-slate-700">#TX-00481</td>
                                <td class="p-4">Siti Aminah</td>
                                <td class="p-4">Permintaan Akun Aplikasi</td>
                                <td class="p-4">02 Jul 2026</td>
                                <td class="p-4">
                                    <span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Diproses</span>
                                </td>
                                <td class="p-4 pr-6 text-center">
                                    <button class="text-slate-500 hover:text-slate-700 font-semibold text-xs px-3 py-1.5 bg-slate-100 rounded-lg transition">Detail</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 pl-6 font-semibold text-slate-700">#TX-00480</td>
                                <td class="p-4">Budi Setiawan</td>
                                <td class="p-4">Perbaikan Hardware Ruang Rapat</td>
                                <td class="p-4">01 Jul 2026</td>
                                <td class="p-4">
                                    <span class="bg-green-100 text-green-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Selesai</span>
                                </td>
                                <td class="p-4 pr-6 text-center">
                                    <button class="text-slate-500 hover:text-slate-700 font-semibold text-xs px-3 py-1.5 bg-slate-100 rounded-lg transition">Detail</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

</body>
</html>