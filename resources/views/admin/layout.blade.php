<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - @yield('title', 'Dashboard Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans antialiased min-h-screen flex">

    <!-- ================= SIDEBAR ================= -->
    <aside class="w-64 bg-[#0a2540] text-white flex flex-col shrink-0 min-h-screen shadow-xl sticky top-0 h-screen">
        <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-700/60 shrink-0">
            <img src="{{ asset('image/esdm.png') }}" alt="Logo" class="w-9 h-9 object-contain">
            <div>
                <h1 class="font-black text-sm tracking-wider">ADMIN PANEL</h1>
                <span class="text-[9px] text-amber-400 uppercase font-bold tracking-widest block">Sistem Tiketing</span>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            @php
                $navActive   = 'nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition bg-amber-400 text-[#0a2540]';
                $navInactive = 'nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition text-slate-300 hover:bg-slate-800 hover:text-white';
            @endphp

            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? $navActive : $navInactive }}">
                <i class="fa-solid fa-chart-line text-sm"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? $navActive : $navInactive }}">
                <i class="fa-solid fa-user-check text-sm"></i>
                <span>Persetujuan Akun</span>
                @if(($notifPendingUsers ?? collect())->count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $notifPendingUsers->count() }}</span>
                @endif
            </a>

            <a href="{{ route('admin.tickets.index') }}" class="{{ request()->routeIs('admin.tickets.index') ? $navActive : $navInactive }}">
                <i class="fa-solid fa-ticket text-sm"></i>
                <span>Manajemen Tiket</span>
                @php $totalTiketNotif = (($notifResolvedAdmin ?? collect())->count()); @endphp
                @if($totalTiketNotif > 0)
                    <span class="ml-auto bg-amber-500 text-[#0a2540] text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $totalTiketNotif }}</span>
                @endif
            </a>

            <a href="{{ route('admin.tickets.all') }}" class="{{ request()->routeIs('admin.tickets.all') ? $navActive : $navInactive }}">
                <i class="fa-solid fa-list-check text-sm"></i>
                <span>Semua Tiket</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-700/60 shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar Admin</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 p-6 lg:p-8 space-y-6 overflow-y-auto">

        <!-- Header Utama -->
        <div class="flex justify-between items-center bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
            <div>
                <h2 class="text-xl font-bold text-slate-800">@yield('page-title', 'Dashboard Admin')</h2>
                <p class="text-xs text-slate-400 mt-0.5">@yield('page-desc', 'Kelola verifikasi pendaftaran akun dan alur penanganan tiket helpdesk')</p>
            </div>

            <div class="flex items-center gap-3">
                {{-- ===== NOTIFIKASI BELL ADMIN ===== --}}
                <div class="relative" id="notifWrapperAdmin">
                    <button type="button" id="notifBtnAdmin"
                        class="relative bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 w-10 h-10 flex items-center justify-center rounded-xl transition cursor-pointer">
                        <i class="fa-solid fa-bell text-sm"></i>
                        @if(($notifCountAdmin ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $notifCountAdmin > 9 ? '9+' : $notifCountAdmin }}
                        </span>
                        @endif
                    </button>

                    <div id="notifMenuAdmin" class="hidden absolute right-0 mt-2 w-80 max-w-[90vw] bg-white border border-slate-200 shadow-2xl rounded-2xl z-50 overflow-hidden">
                        <div class="p-3 border-b border-slate-100 bg-slate-50">
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Notifikasi Admin</p>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                            @forelse(($notifNewTicketsAdmin ?? []) as $t)
                            <a href="{{ route('admin.ticket.show', $t->id) }}" class="w-full text-left flex gap-2.5 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }} dibuat oleh {{ $t->pelapor->nama_lengkap ?? '-' }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 truncate">Kategori: {{ $t->kategori }} · Prioritas: {{ $t->prioritas }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $t->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty @endforelse

                            @forelse(($notifResolvedAdmin ?? []) as $t)
                            <a href="{{ route('admin.ticket.show', $t->id) }}" class="w-full text-left flex gap-2.5 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }} siap ditutup
                                    </p>
                                    <p class="text-[11px] text-slate-500 truncate">Pelapor: {{ $t->pelapor->nama_lengkap ?? '-' }} · PJ: {{ $t->penanggung_jawab }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $t->updated_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty @endforelse

                            @forelse(($notifUserClosedAdmin ?? []) as $t)
                            <a href="{{ route('admin.ticket.show', $t->id) }}" class="w-full text-left flex gap-2.5 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-slash text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }} ditutup pelapor
                                    </p>
                                    <p class="text-[11px] text-slate-500 truncate">Pelapor: {{ $t->pelapor->nama_lengkap ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $t->updated_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty @endforelse

                            @forelse(($notifPendingUsers ?? []) as $u)
                            <a href="{{ route('admin.users.index') }}" class="w-full text-left flex gap-2.5 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-plus text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">Pendaftaran baru — {{ $u->nama_lengkap }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $u->email }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $u->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty @endforelse
                        </div>
                    </div>
                </div>

                <span class="bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200">
                    <i class="fa-solid fa-user-shield text-amber-500 me-1"></i> Mode Admin
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 p-4 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-200 text-red-800 p-4 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')

    </main>

    <script>
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function () {
                window.location.href = this.getAttribute('data-url');
            });
        });
        document.querySelectorAll('.action-buttons').forEach(container => {
            container.addEventListener('click', function (e) { e.stopPropagation(); });
        });

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