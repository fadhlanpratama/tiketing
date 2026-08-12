<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM Tiketing - @yield('title', 'Dashboard Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Utility untuk menghilangkan scrollbar abu-abu bawaan di elemen custom scroll */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased min-h-screen flex flex-col md:flex-row relative text-slate-800">

    <!-- ================= MOBILE OVERLAY ================= -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

    <!-- ================= SIDEBAR ================= -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0a2540] text-white flex flex-col shrink-0 h-screen shadow-2xl transition-transform duration-300 transform -translate-x-full md:translate-x-0 md:sticky md:top-0">
        <!-- Logo Section -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-700/60 shrink-0 bg-[#071d33]">
            <div class="flex items-center gap-3">
                <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="w-9 h-9 object-contain drop-shadow">
                <div>
                    <h1 class="font-black text-xs tracking-widest text-white uppercase">SISTEM TIKETING</h1>
                    <span class="text-[9px] text-amber-400 uppercase font-bold tracking-widest block">PORTAL ADMIN</span>
                </div>
            </div>
            <button id="closeSidebar" class="md:hidden text-slate-400 hover:text-amber-400 p-2 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto no-scrollbar">
            @php
                $navActive   = 'w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition-all bg-amber-400 text-[#0a2540] shadow-md shadow-amber-400/20';
                $navInactive = 'w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold transition-all text-slate-300 hover:bg-slate-800/80 hover:text-white';
            @endphp

            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? $navActive : $navInactive }}">
                <i class="fa-solid fa-chart-line text-sm w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? $navActive : $navInactive }}">
                <i class="fa-solid fa-user-check text-sm w-5 text-center"></i>
                <span>Persetujuan Akun</span>
                @if(($notifPendingUsers ?? collect())->count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow-sm">
                        {{ $notifPendingUsers->count() }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.tickets.index') }}" class="{{ request()->routeIs('admin.tickets.index') ? $navActive : $navInactive }}">
                <i class="fa-solid fa-ticket text-sm w-5 text-center"></i>
                <span>Manajemen Tiket</span>
                @php $totalTiketNotif = (($notifResolvedAdmin ?? collect())->count()); @endphp
                @if($totalTiketNotif > 0)
                    <span class="ml-auto bg-amber-500 text-[#0a2540] text-[10px] px-2 py-0.5 rounded-full font-bold shadow-sm">
                        {{ $totalTiketNotif }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.tickets.all') }}" class="{{ request()->routeIs('admin.tickets.all') ? $navActive : $navInactive }}">
                <i class="fa-solid fa-list-check text-sm w-5 text-center"></i>
                <span>Semua Tiket</span>
            </a>
        </nav>

        <!-- Logout Section -->
        <div class="p-4 border-t border-slate-700/60 shrink-0 bg-[#071d33]/50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-500/10 hover:bg-red-600 text-red-400 hover:text-white px-4 py-2.5 rounded-xl text-xs font-bold transition border border-red-500/20 hover:border-transparent cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar Admin</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 overflow-y-auto min-w-0">

        <!-- HEADER UTAMA (Sesuai Aksentuasi Tema Navy Blue + Amber Gold) -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-[#0a2540] to-[#12385f] text-white p-5 rounded-2xl shadow-lg border-b-4 border-amber-400">
            <div class="flex items-center gap-3">
                <!-- Tombol Sidebar Mobile -->
                <button id="openSidebar" class="md:hidden bg-white/10 hover:bg-white/20 text-amber-400 p-2.5 rounded-xl transition cursor-pointer">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>

                <div>
                    <h2 class="text-lg sm:text-xl font-black tracking-wide text-white">@yield('page-title', 'Dashboard Admin')</h2>
                    <p class="text-xs text-slate-300 mt-0.5">@yield('page-desc', 'Kelola verifikasi pendaftaran akun dan alur penanganan tiket helpdesk')</p>
                </div>
            </div>

            <div class="flex items-center gap-3 self-end sm:self-auto">
                {{-- ===== NOTIFIKASI BELL ADMIN ===== --}}
                <div class="relative" id="notifWrapperAdmin">
                    <button type="button" id="notifBtnAdmin"
                        class="relative bg-white/10 hover:bg-amber-400 hover:text-[#0a2540] text-amber-400 w-10 h-10 flex items-center justify-center rounded-xl transition cursor-pointer shadow-sm">
                        <i class="fa-solid fa-bell text-sm"></i>
                        @if(($notifCountAdmin ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center ring-2 ring-[#0a2540]">
                            {{ $notifCountAdmin > 9 ? '9+' : $notifCountAdmin }}
                        </span>
                        @endif
                    </button>

                    <!-- Dropdown Notifikasi -->
                    <div id="notifMenuAdmin" class="hidden absolute right-0 mt-3 w-80 max-w-[90vw] bg-white border border-slate-200 shadow-2xl rounded-2xl z-50 overflow-hidden text-slate-800">
                        <div class="p-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                            <p class="text-xs font-bold text-[#0a2540] uppercase tracking-wide">Notifikasi Admin</p>
                            <span class="text-[10px] bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded-full">Terbaru</span>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 no-scrollbar">
                            @forelse(($notifNewTicketsAdmin ?? []) as $t)
                            <a href="{{ route('admin.ticket.show', $t->id) }}" class="w-full text-left flex gap-3 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }} dibuat oleh {{ $t->pelapor->nama_lengkap ?? '-' }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 truncate">Kategori: {{ $t->kategori }} · Prioritas: {{ $t->prioritas }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5"><i class="fa-regular fa-clock me-1"></i>{{ $t->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty @endforelse

                            @forelse(($notifResolvedAdmin ?? []) as $t)
                            <a href="{{ route('admin.ticket.show', $t->id) }}" class="w-full text-left flex gap-3 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }} siap ditutup
                                    </p>
                                    <p class="text-[11px] text-slate-500 truncate">Pelapor: {{ $t->pelapor->nama_lengkap ?? '-' }} · PJ: {{ $t->penanggung_jawab }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5"><i class="fa-regular fa-clock me-1"></i>{{ $t->updated_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty @endforelse

                            @forelse(($notifUserClosedAdmin ?? []) as $t)
                            <a href="{{ route('admin.ticket.show', $t->id) }}" class="w-full text-left flex gap-3 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-slash text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }} ditutup pelapor
                                    </p>
                                    <p class="text-[11px] text-slate-500 truncate">Pelapor: {{ $t->pelapor->nama_lengkap ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5"><i class="fa-regular fa-clock me-1"></i>{{ $t->updated_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty @endforelse

                            @forelse(($notifPendingUsers ?? []) as $u)
                            <a href="{{ route('admin.users.index') }}" class="w-full text-left flex gap-3 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-plus text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">Pendaftaran baru — {{ $u->nama_lengkap }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $u->email }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5"><i class="fa-regular fa-clock me-1"></i>{{ $u->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty @endforelse

                            @if(empty($notifNewTicketsAdmin) && empty($notifResolvedAdmin) && empty($notifUserClosedAdmin) && empty($notifPendingUsers))
                                <div class="p-6 text-center text-slate-400">
                                    <i class="fa-regular fa-bell-slash text-xl mb-1 block"></i>
                                    <p class="text-xs font-medium">Tidak ada notifikasi baru</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Badge Mode -->
                <span class="bg-amber-400 text-[#0a2540] text-xs font-extrabold px-3 py-2 rounded-xl flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-user-shield"></i> Mode Admin
                </span>
            </div>
        </div>

        <!-- Flash Session Alerts -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-base shrink-0"></i>
                <span class="flex-1">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-base shrink-0"></i>
                <span class="flex-1">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')

    </main>

    <script>
        // Toggle Sidebar Mobile
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }

        if (openSidebarBtn) openSidebarBtn.addEventListener('click', openSidebar);
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

        // Clickable Table Rows
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function () {
                window.location.href = this.getAttribute('data-url');
            });
        });
        document.querySelectorAll('.action-buttons').forEach(container => {
            container.addEventListener('click', function (e) { e.stopPropagation(); });
        });

        // Dropdown Notifikasi Admin
        const notifBtnAdmin = document.getElementById('notifBtnAdmin');
        const notifMenuAdmin = document.getElementById('notifMenuAdmin');
        if (notifBtnAdmin && notifMenuAdmin) {
            notifBtnAdmin.addEventListener('click', (e) => {
                e.stopPropagation();
                notifMenuAdmin.classList.toggle('hidden');
            });
            document.addEventListener('click', () => notifMenuAdmin.classList.add('hidden'));
            notifMenuAdmin.addEventListener('click', (e) => e.stopPropagation());
        }
    </script>

    @stack('scripts')
</body>
</html>