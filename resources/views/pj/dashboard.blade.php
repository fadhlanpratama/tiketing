<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Dashboard PJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased flex flex-col">

    <header class="bg-[#0a2540] text-white sticky top-0 z-30 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('image/esdm.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                <div>
                    <h1 class="text-white font-black tracking-wider text-base sm:text-lg leading-tight">SISTEM TIKETING</h1>
                    <span class="text-[9px] sm:text-[10px] text-amber-400 uppercase font-bold tracking-widest block">Portal Penanggung Jawab</span>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <div class="text-right hidden sm:flex items-center gap-3">
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Penanggung Jawab</p>
                        <p class="text-sm font-bold text-white mt-0.5">{{ session('nama_lengkap', 'PJ ESDM') }}</p>
                    </div>
                </div>

                {{-- ===== NOTIFIKASI BELL (DESKTOP & MOBILE) ===== --}}
                <div class="relative" id="notifWrapper">
                    <button type="button" id="notifBtn"
                        class="relative bg-slate-700/50 hover:bg-slate-700 text-slate-300 hover:text-white w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl transition cursor-pointer">
                        <i class="fa-solid fa-bell text-sm"></i>
                        @if(($notifCount ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $notifCount > 9 ? '9+' : $notifCount }}
                        </span>
                        @endif
                    </button>

                    {{-- Dropdown Menu Notifikasi dengan Responsif (Mobile-friendly position & sizing) --}}
                    <div id="notifMenu" class="hidden absolute -right-12 sm:right-0 mt-2 w-[85vw] sm:w-80 max-w-xs sm:max-w-none bg-white border border-slate-200 shadow-2xl rounded-2xl z-50 overflow-hidden">
                        <div class="p-3 border-b border-slate-100 bg-slate-50">
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Notifikasi</p>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                            @forelse(($notifMessages ?? []) as $msg)
                            <a href="{{ route('pj.ticket.show', $msg->ticket_id) }}" class="flex gap-2.5 p-3 hover:bg-slate-50 transition">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-comment-dots text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        Pesan baru — #TKT-{{ str_pad($msg->ticket_id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $msg->sender_nama }}: {{ $msg->pesan ?? 'Mengirim foto' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $msg->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty
                            @endforelse

                            @forelse(($notifClosed ?? []) as $t)
                            <a href="{{ route('pj.ticket.show', $t->id) }}" class="flex gap-2.5 p-3 hover:bg-slate-50 transition">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-slash text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        Tiket ditutup — #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                    <p class="text-[11px] text-slate-500">Dibatalkan oleh pelapor</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $t->updated_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty
                            @endforelse

                            @forelse(($notifAssignedPj ?? []) as $t)
                            <a href="{{ route('pj.ticket.show', $t->id) }}" class="flex gap-2.5 p-3 hover:bg-slate-50 transition">
                                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-clipboard-list text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        Tugas baru — #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                    <p class="text-[11px] text-slate-500">Anda memiliki tugas baru untuk tiket ini</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $t->updated_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty
                            @endforelse

                            @forelse(($notifAdminClosedPj ?? []) as $t)
                            <a href="{{ route('pj.ticket.show', $t->id) }}" class="flex gap-2.5 p-3 hover:bg-slate-50 transition">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        Tiket ditutup Admin — #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                    <p class="text-[11px] text-slate-500">Tiket telah resmi ditutup oleh admin</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $t->updated_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty
                            @endforelse

                            @if(($notifCount ?? 0) === 0)
                            <div class="p-6 text-center">
                                <i class="fa-regular fa-bell-slash text-slate-300 text-2xl mb-2"></i>
                                <p class="text-xs text-slate-400">Tidak ada notifikasi baru.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{route('pj.profile.edit')}}" class="hidden sm:flex bg-slate-700/50 hover:bg-slate-700 text-slate-300 hover:text-white w-9 h-9 items-center justify-center rounded-xl transition text-xs" title="Edit Profil">
                    <i class="fa-solid fa-user-gear text-sm"></i>
                </a>
                
                <a href="{{route('pj.profile.edit')}}" class="sm:hidden bg-slate-700/40 text-amber-400 w-10 h-10 flex flex-col items-center justify-center rounded-xl border border-slate-700/60 active:scale-95 transition" title="Edit Profil">
                    <i class="fa-solid fa-user-gear text-sm"></i>
                    <span class="text-[8px] font-bold tracking-tight mt-0.5">Profil</span>
                </a>

                <div class="h-6 w-px bg-slate-700"></div>
                
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white px-3.5 py-2.5 sm:px-4 rounded-xl text-xs font-semibold transition shadow-sm cursor-pointer">
                        <i class="fa-solid fa-right-from-bracket"></i> 
                        <span class="hidden md:inline">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <div class="bg-gradient-to-r from-[#0a2540] to-[#16406c] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative">
            <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none">
                <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            </div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                <div>
                    <span class="bg-amber-400 text-[#0a2540] text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md mb-3 inline-block">Akses Penanggung Jawab</span>
                    <h2 class="text-xl sm:text-3xl font-extrabold tracking-tight">Kelola Penugasan Anda</h2>
                    <p class="text-slate-300 text-xs sm:text-sm mt-1">Tinjau, tangani, dan selesaikan setiap tiket yang menjadi tanggung jawab Anda.</p>
                </div>

                <div class="relative shrink-0" id="slaGaugeWrapper">
                    <button type="button" id="slaGaugeBtn"
                        class="w-full sm:w-auto text-left bg-white/10 hover:bg-white/15 backdrop-blur border border-white/20 rounded-2xl p-4 flex items-center gap-3 transition cursor-pointer {{ $slaTerlambat > 0 ? 'ring-2 ring-red-400/40' : '' }}">
                        <svg width="52" height="32" viewBox="0 0 64 40" class="shrink-0">
                            <path d="M4,32 A28,28 0 0 1 18,7.75" fill="none" stroke="#86efac" stroke-width="6" stroke-linecap="round"/>
                            <path d="M18,7.75 A28,28 0 0 1 46,7.75" fill="none" stroke="#fcd34d" stroke-width="6" stroke-linecap="round"/>
                            <path d="M46,7.75 A28,28 0 0 1 60,32" fill="none" stroke="#fca5a5" stroke-width="6" stroke-linecap="round"/>
                            <line x1="32" y1="32" x2="32" y2="10" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round"
                                transform="rotate({{ $slaNeedleDeg }},32,32)"/>
                            <circle cx="32" cy="32" r="3" fill="#ffffff"/>
                        </svg>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold text-slate-300 uppercase tracking-wider">Melebihi SLA</p>
                            <h3 class="text-lg font-bold {{ $slaTerlambat > 0 ? 'text-red-300' : 'text-white' }} mt-0.5">{{ $slaTerlambat }} Tiket</h3>
                        </div>
                        <i class="fa-solid fa-chevron-down text-slate-300 text-xs ml-2"></i>
                    </button>

                    <div id="slaGaugeMenu" class="hidden absolute right-0 left-0 sm:left-auto sm:w-96 mt-2 bg-white border border-slate-200 shadow-2xl rounded-2xl z-50 overflow-hidden text-left">
                        <div class="p-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Tiket Melebihi SLA</p>
                            <span class="text-[10px] text-slate-400">{{ $slaPercentage }}% dari tiket berjalan</span>
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                            @forelse($ticketsOverSla as $t)
                            <a href="{{ route('pj.ticket.show', $t->id) }}" class="flex items-center justify-between gap-3 p-3 hover:bg-slate-50 transition">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">#TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $t->kategori }} — {{ $t->sub_kategori }}</p>
                                </div>
                                <span class="shrink-0 text-[11px] font-bold text-red-700 bg-red-50 border border-red-100 px-2 py-1 rounded-lg">
                                    {{ $t->sla_badge['label'] ?? '-' }}
                                </span>
                            </a>
                            @empty
                            <div class="p-6 text-center">
                                <i class="fa-regular fa-circle-check text-green-300 text-2xl mb-2"></i>
                                <p class="text-xs text-slate-400">Tidak ada tiket yang melebihi SLA saat ini.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== BANNER PEMBERITAHUAN SLA BISA DIKLIK (FILTER PRIORITAS) ===== --}}
        @php 
            $currentPrioritas = strtolower($prioritasFilter ?? 'semua'); 
            $currentStatus = request('status');
        @endphp

        <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 bg-amber-400/20 text-amber-700 rounded-xl flex items-center justify-center shrink-0 text-base mt-0.5 md:mt-0">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        Target Waktu Penyelesaian (SLA)
                    </h4>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                        Waktu mulai dihitung saat status <span class="font-bold text-slate-700">In Progress</span> (setelah tombol <i class="text-slate-500">Mulai Kerjakan</i> ditekan) hingga tiket berstatus <span class="font-bold text-slate-700">Resolved</span>. Klik kartu untuk memfilter tabel.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 w-full md:w-auto shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-amber-200/50">
                {{-- KARTU PRIORITAS TINGGI --}}
                <a href="{{ route('pj.dashboard', array_filter(['status' => $currentStatus, 'prioritas' => $currentPrioritas === 'tinggi' ? null : 'Tinggi'])) }}"
                class="p-2 sm:p-2.5 text-center rounded-xl border transition-all duration-200 hover:-translate-y-0.5 shadow-sm {{ $currentPrioritas === 'tinggi' ? 'bg-red-500 text-white border-red-600 ring-2 ring-red-200' : 'bg-white/80 border-red-200/80 hover:bg-red-50' }}">
                    <span class="block text-[10px] font-bold uppercase {{ $currentPrioritas === 'tinggi' ? 'text-red-100' : 'text-red-600' }}">Tinggi</span>
                    <span class="block text-[11px] font-extrabold mt-0.5 {{ $currentPrioritas === 'tinggi' ? 'text-white' : 'text-slate-800' }}">1 Hari Kerja</span>
                </a>

                {{-- KARTU PRIORITAS SEDANG --}}
                <a href="{{ route('pj.dashboard', array_filter(['status' => $currentStatus, 'prioritas' => $currentPrioritas === 'sedang' ? null : 'Sedang'])) }}"
                class="p-2 sm:p-2.5 text-center rounded-xl border transition-all duration-200 hover:-translate-y-0.5 shadow-sm {{ $currentPrioritas === 'sedang' ? 'bg-amber-500 text-white border-amber-600 ring-2 ring-amber-200' : 'bg-white/80 border-amber-200/80 hover:bg-amber-50' }}">
                    <span class="block text-[10px] font-bold uppercase {{ $currentPrioritas === 'sedang' ? 'text-amber-100' : 'text-amber-600' }}">Sedang</span>
                    <span class="block text-[11px] font-extrabold mt-0.5 {{ $currentPrioritas === 'sedang' ? 'text-white' : 'text-slate-800' }}">3 Hari Kerja</span>
                </a>

                {{-- KARTU PRIORITAS RENDAH --}}
                <a href="{{ route('pj.dashboard', array_filter(['status' => $currentStatus, 'prioritas' => $currentPrioritas === 'rendah' ? null : 'Rendah'])) }}"
                class="p-2 sm:p-2.5 text-center rounded-xl border transition-all duration-200 hover:-translate-y-0.5 shadow-sm {{ $currentPrioritas === 'rendah' ? 'bg-slate-700 text-white border-slate-800 ring-2 ring-slate-200' : 'bg-white/80 border-slate-200/80 hover:bg-slate-100' }}">
                    <span class="block text-[10px] font-bold uppercase {{ $currentPrioritas === 'rendah' ? 'text-slate-300' : 'text-slate-600' }}">Rendah</span>
                    <span class="block text-[11px] font-extrabold mt-0.5 {{ $currentPrioritas === 'rendah' ? 'text-white' : 'text-slate-800' }}">7 Hari Kerja</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-5">
            <a href="{{ route('pj.dashboard', ['status' => $statusFilter === 'Open' ? 'semua' : 'Open']) }}"
                class="bg-white p-5 rounded-2xl shadow-sm border flex items-center gap-4 transition hover:shadow-md hover:-translate-y-0.5 {{ $statusFilter === 'Open' ? 'border-blue-400 ring-2 ring-blue-100' : 'border-slate-100' }}">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-folder-open"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dalam Antrian</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $menunggu }} Tiket</h3>
                </div>
            </a>
            <a href="{{ route('pj.dashboard', ['status' => $statusFilter === 'In Progress' ? 'semua' : 'In Progress']) }}"
                class="bg-white p-5 rounded-2xl shadow-sm border flex items-center gap-4 transition hover:shadow-md hover:-translate-y-0.5 {{ $statusFilter === 'In Progress' ? 'border-amber-400 ring-2 ring-amber-100' : 'border-slate-100' }}">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-spinner"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dalam Proses</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $diproses }} Tiket</h3>
                </div>
            </a>
           <a href="{{ route('pj.dashboard', ['status' => $statusFilter === 'Selesai' ? 'semua' : 'Selesai']) }}"
                class="bg-white p-5 rounded-2xl shadow-sm border flex items-center gap-4 transition hover:shadow-md hover:-translate-y-0.5 {{ $statusFilter === 'Selesai' ? 'border-green-400 ring-2 ring-green-100' : 'border-slate-100' }}">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Selesai</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $selesai }} Tiket</h3>
                </div>
            </a>
            {{-- KARTU BARU: DIBATALKAN OLEH PELAPOR --}}
            <a href="{{ route('pj.dashboard', ['status' => $statusFilter === 'Dibatalkan' ? 'semua' : 'Dibatalkan']) }}"
                class="bg-white p-5 rounded-2xl shadow-sm border flex items-center gap-4 transition hover:shadow-md hover:-translate-y-0.5 {{ $statusFilter === 'Dibatalkan' ? 'border-slate-400 ring-2 ring-slate-200' : 'border-slate-100' }}">
                <div class="w-12 h-12 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-user-slash"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dibatalkan</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $dibatalkan }} Tiket</h3>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 relative">
           <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Tiket yang Ditugaskan Kepada Anda</h3>
                    <p class="text-xs text-slate-400">Status pelacakan real-time untuk penugasan Anda</p>
                </div>

                <div class="relative w-full sm:w-56" id="statusFilterWrapper">
                    <button type="button" id="statusFilterBtn"
                        class="w-full bg-slate-50 border border-slate-200 p-2.5 pr-10 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition-all text-xs font-semibold text-slate-600 flex justify-between items-center cursor-pointer hover:bg-slate-100/60">
                        <span id="statusFilterLabel"><i class="fa-solid fa-filter mr-1.5 text-slate-400"></i>
                            @if($statusFilter === 'semua') Semua Status
                            @elseif($statusFilter === 'Selesai') Resolved and Closed
                            @elseif($statusFilter === 'Dibatalkan') Dibatalkan oleh Pelapor
                            @else {{ $statusFilter }}
                            @endif
                        </span>
                        <svg id="statusFilterArrow" class="h-4 w-4 text-slate-400 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div id="statusFilterMenu" class="hidden absolute right-0 top-full mt-1 w-full bg-white border border-slate-200 shadow-2xl rounded-xl p-1 space-y-0.5 text-xs text-slate-700 font-medium z-50">
                        <div data-value="semua" class="status-filter-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Semua Status</div>
                        <div data-value="Open" class="status-filter-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Open</div>
                        <div data-value="In Progress" class="status-filter-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">In Progress</div>
                        <div data-value="Selesai" class="status-filter-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Resolved and Closed</div>
                        <div data-value="Dibatalkan" class="status-filter-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Dibatalkan oleh Pelapor</div>
                    </div>
                </div>
            </div>

            {{-- ===== VERSI DESKTOP ===== --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-semibold text-xs tracking-wider border-b border-slate-100">
                            <th class="p-4 pl-6">ID TIKET</th>
                            <th class="p-4">KATEGORI PERMINTAAN</th>
                            <th class="p-4">PELAPOR</th>
                            <th class="p-4">PRIORITAS</th>
                            <th class="p-4">STATUS</th>
                            <th class="p-4">SLA</th>
                            <th class="p-4 pr-6 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                        @forelse($tickets as $ticket)
                       <tr class="hover:bg-slate-50/50 transition cursor-pointer clickable-row" data-url="{{ route('pj.ticket.show', $ticket->id) }}">
                            <td class="p-4 pl-6 font-semibold text-slate-700">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}</p>
                                <span class="text-[11px] text-slate-400">Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</span>
                            </td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">{{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                                <span class="text-[11px] text-slate-400">{{ $ticket->pelapor->divisi ?? '-' }}</span>
                            </td>
                            <td class="p-4">
                                @if(strtolower($ticket->prioritas) == 'tinggi')
                                    <span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded-lg text-xs border border-red-100"><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $ticket->prioritas }}</span>
                                @elseif(strtolower($ticket->prioritas) == 'sedang')
                                    <span class="text-amber-600 font-bold bg-amber-50 px-2 py-1 rounded-lg text-xs border border-amber-100"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $ticket->prioritas }}</span>
                                @else
                                    <span class="text-slate-600 font-bold bg-slate-100 px-2 py-1 rounded-lg text-xs"><i class="fa-solid fa-circle-info mr-1"></i>{{ $ticket->prioritas }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($ticket->status == 'Open')
                                    <span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Open</span>
                                @elseif($ticket->status == 'In Progress')
                                    <span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">In Progress</span>
                                @elseif($ticket->status == 'Resolved')
                                    <span class="bg-green-100 text-green-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Resolved</span>
                                @elseif($ticket->status == 'Closed')
                                    <span class="bg-slate-100 text-slate-600 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Closed</span>
                                    @if($ticket->closed_by === 'user')
                                        <span class="text-[10px] text-slate-600 italic block mt-1"><i class="fa-solid fa-user-slash"></i> Dibatalkan oleh Pelapor</span>
                                    @endif
                                @endif
                            </td>
                            <td class="p-4">
                                @php $slaBadge = $ticket->sla_badge; @endphp
                                @if(!$slaBadge)
                                    <span class="text-slate-300 text-xs italic">-</span>
                                @elseif($slaBadge['terlambat'])
                                    <span class="inline-flex items-center gap-1 text-red-700 font-bold bg-red-50 px-2 py-1 rounded-lg text-[11px] border border-red-100">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        {{ $slaBadge['sedang_proses'] ? 'Lewat SLA ' : 'Terlambat ' }}{{ $slaBadge['label'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-green-700 font-bold bg-green-50 px-2 py-1 rounded-lg text-[11px] border border-green-100">
                                        <i class="fa-solid fa-circle-check"></i>
                                        {{ $slaBadge['sedang_proses'] ? 'Dalam SLA' : 'Tepat Waktu' }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 pr-6" onclick="event.stopPropagation()">
                                {{-- Tombol Aksi --}}
                                <div class="flex items-center justify-center gap-2 action-buttons">
                                    @if($ticket->status == 'Open')
                                        <button type="button" class="btn-mulai bg-amber-400 hover:bg-amber-300 text-[#0a2540] font-bold text-xs px-3 py-2 rounded-lg transition"
                                            data-id="{{ $ticket->id }}"
                                            data-code="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}"
                                            data-prioritas="{{ $ticket->prioritas }}"
                                            data-kategori="{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}">
                                            Mulai Kerjakan
                                        </button>
                                    @elseif($ticket->status == 'In Progress')
                                        <button type="button" class="btn-selesaikan bg-green-500 hover:bg-green-600 text-white font-bold text-xs px-3 py-2 rounded-lg transition"
                                            data-id="{{ $ticket->id }}" data-code="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}">
                                            Selesaikan
                                        </button>
                                    @else
                                        <span class="text-slate-300 text-xs italic">Selesai</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="p-8 text-center text-slate-400 text-sm">Belum ada tiket yang ditugaskan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ===== VERSI MOBILE ===== --}}
            <div class="md:hidden p-4 space-y-4">
                @forelse($tickets as $ticket)
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-3 cursor-pointer clickable-row" data-url="{{ route('pj.ticket.show', $ticket->id) }}">
                    
                    {{-- Header Kartu Mobile: ID Tiket & Status Saja (Samping Dikosongkan) --}}
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-slate-700">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                        
                        @if($ticket->status == 'Open')
                            <span class="bg-amber-100 text-amber-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Open</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="bg-blue-100 text-blue-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">In Progress</span>
                        @elseif($ticket->status == 'Resolved')
                            <span class="bg-green-100 text-green-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Resolved</span>
                        @elseif($ticket->status == 'Closed')
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Closed</span>
                        @endif
                    </div>

                    @if($ticket->status == 'Closed' && $ticket->closed_by === 'user')
                        <p class="text-[10px] text-slate-600 italic mt-1"><i class="fa-solid fa-user-slash"></i> Dibatalkan oleh Pelapor</p>
                    @endif

                    {{-- Badge SLA Mobile --}}
                    @php $slaBadgeMobile = $ticket->sla_badge; @endphp
                    @if($slaBadgeMobile)
                        <div>
                            @if($slaBadgeMobile['terlambat'])
                                <span class="inline-flex items-center gap-1 text-red-700 font-bold bg-red-50 px-2 py-1 rounded-lg text-[11px] border border-red-100">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $slaBadgeMobile['sedang_proses'] ? 'Lewat SLA ' : 'Terlambat ' }}{{ $slaBadgeMobile['label'] }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-green-700 font-bold bg-green-50 px-2 py-1 rounded-lg text-[11px] border border-green-100">
                                    <i class="fa-solid fa-circle-check"></i>
                                    {{ $slaBadgeMobile['sedang_proses'] ? 'Dalam SLA' : 'Tepat Waktu' }}
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Detail Informasi Tiket + Prioritas di Bawah --}}
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">{{ $ticket->kategori }}</h4>
                        <p class="text-xs text-slate-600 mt-0.5">{{ $ticket->sub_kategori }}</p>
                        <p class="text-[11px] text-slate-400 mt-2"><i class="fa-solid fa-user"></i> Pelapor: {{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5"><i class="fa-regular fa-clock"></i> Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</p>
                        
                        {{-- POSISI PRIORITAS DI BAWAH (SESUAI GAMBAR USER) --}}
                        <p class="text-[10px] text-slate-500 font-medium mt-1">
                            <i class="fa-solid fa-layer-group mr-1 text-slate-400"></i>Prioritas: 
                            @if(strtolower($ticket->prioritas) == 'tinggi')
                                <span class="text-red-600 font-bold">Tinggi</span>
                            @elseif(strtolower($ticket->prioritas) == 'sedang')
                                <span class="text-amber-600 font-bold">Sedang</span>
                            @else
                                <span class="text-slate-700 font-bold">Rendah</span>
                            @endif
                        </p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="grid grid-cols-1 gap-1.5 pt-2 border-t border-slate-200/60 action-buttons">
                        @if($ticket->status == 'Open')
                            <button type="button" class="btn-mulai w-full bg-amber-400 hover:bg-amber-300 text-[#0a2540] font-bold text-xs py-2 rounded-xl transition"
                                data-id="{{ $ticket->id }}"
                                data-code="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}"
                                data-prioritas="{{ $ticket->prioritas }}"
                                data-kategori="{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}">
                                Mulai Kerjakan
                            </button>
                        @elseif($ticket->status == 'In Progress')
                            <button type="button" class="btn-selesaikan w-full bg-green-500 hover:bg-green-600 text-white font-bold text-xs py-2 rounded-xl transition"
                                data-id="{{ $ticket->id }}" data-code="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}">
                                Selesaikan
                            </button>
                        @else
                            <span class="text-slate-300 text-xs italic text-center py-2 block">Selesai</span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-center text-slate-400 text-sm py-4">Belum ada tiket yang ditugaskan.</p>
                @endforelse
            </div>
            
            <div class="p-4 border-t border-slate-100">
    @if($tickets->hasPages())
    <nav class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-xs text-slate-400">
            Menampilkan <span class="font-semibold text-slate-600">{{ $tickets->firstItem() }}</span>
            &ndash; <span class="font-semibold text-slate-600">{{ $tickets->lastItem() }}</span>
            dari <span class="font-semibold text-slate-600">{{ $tickets->total() }}</span> tiket
        </p>

        <div class="flex items-center gap-1.5">
                        @if ($tickets->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-300 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $tickets->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-slate-300 transition">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </a>
                        @endif
                        
                        @php
                            $maxVisible = 5;
                            $current = $tickets->currentPage();
                            $last = $tickets->lastPage();
                            
                            $startPage = (floor(($current - 1) / $maxVisible) * $maxVisible) + 1;
                            $endPage = min($startPage + $maxVisible - 1, $last);
                        @endphp

                        @for ($i = $startPage; $i <= $endPage; $i++)
                            @if ($i == $current)
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-400 text-[#0a2540] text-xs font-bold shadow-sm">{{ $i }}</span>
                            @else
                                <a href="{{ $tickets->url($i) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 text-xs font-semibold hover:bg-slate-50 hover:border-slate-300 transition">{{ $i }}</a>
                            @endif
                        @endfor

                        @if ($tickets->hasMorePages())
                            <a href="{{ $tickets->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-slate-300 transition">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-300 cursor-not-allowed">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </span>
                        @endif
                    </div>
                </nav>
                @endif
            </div>
        </div>
    </main>

    {{-- ==================== MODAL KONFIRMASI MULAI KERJAKAN ==================== --}}
    <div id="mulaiModal" class="fixed inset-0 bg-slate-900/60 items-center justify-center p-4 z-50 hidden transition-all opacity-0 duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-all duration-300">
            <div class="bg-[#0a2540] text-white p-5 flex justify-between items-center">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-amber-400 text-xl"></i>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold tracking-tight">Konfirmasi Mulai Kerjakan</h3>
                        <p class="text-[11px] text-slate-300" id="mulaiTicketCode"></p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-3">
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Kategori Tiket</p>
                        <p class="text-sm font-bold text-slate-800 mt-0.5" id="mulaiKategori">-</p>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-200/70">
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Prioritas</p>
                            <span id="mulaiPrioritasBadge" class="inline-flex items-center gap-1.5 mt-1 text-xs font-bold px-2.5 py-1 rounded-lg border"></span>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Target SLA</p>
                            <p class="text-sm font-bold text-slate-800 mt-1" id="mulaiSlaText">-</p>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-slate-500 leading-relaxed">
                    <i class="fa-solid fa-hourglass-start text-amber-500 mr-1"></i>
                    Perhitungan waktu SLA akan <span class="font-bold text-slate-700">mulai berjalan sejak saat ini</span> setelah Anda menekan tombol konfirmasi. Pastikan Anda benar-benar siap menangani tiket ini sekarang.
                </p>

                <form id="formMulai" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <button type="button" onclick="closeMulaiModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm py-2.5 rounded-xl transition cursor-pointer">Batal</button>
                        <button type="submit" class="bg-amber-400 hover:bg-amber-300 text-[#0a2540] font-bold text-sm py-2.5 rounded-xl transition shadow-md cursor-pointer">Ya, Kerjakan Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL PENYELESAIAN TIKET ==================== --}}
    <div id="selesaikanModal" class="fixed inset-0 bg-slate-900/60 items-center justify-center p-4 z-50 hidden transition-all opacity-0 duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-all duration-300 max-h-[90vh] flex flex-col">
            <div class="bg-[#0a2540] text-white p-5 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-green-400 text-xl"></i>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold tracking-tight">Selesaikan Tiket</h3>
                        <p class="text-[11px] text-slate-300" id="modalTicketCode"></p>
                    </div>
                </div>
            </div>

            <div class="p-6 overflow-y-auto flex-1">
                <p class="text-xs text-slate-400 mb-4"><i class="fa-regular fa-clock"></i> Tanggal selesai akan dicatat otomatis oleh sistem saat ini.</p>

                <div id="selesaikanAlert" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold"></div>

                <form id="formSelesaikan" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Catatan Penyelesaian (opsional)</label>
                    <textarea name="catatan_penyelesaian" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-sm mt-1 mb-4 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition"></textarea>

                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Foto Bukti Penyelesaian <span class="text-red-500">*</span></label>
                    <input type="file" id="bukti_foto_input" name="bukti_foto" accept="image/png, image/jpeg" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-sm mt-1 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-[#0a2540] file:text-white file:text-xs file:font-semibold">
                    <p class="text-[11px] text-slate-400 mt-1.5 mb-4"><i class="fa-solid fa-circle-info"></i> Format JPG/PNG, maksimal 2MB. Foto ini wajib diunggah sesuai SOP sebagai bukti pekerjaan selesai.</p>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="closeSelesaikanModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm py-2.5 rounded-xl transition cursor-pointer">Batal</button>
                        <button type="submit" id="btnSubmitSelesaikan" class="bg-green-500 hover:bg-green-600 text-white font-semibold text-sm py-2.5 rounded-xl transition shadow-md cursor-pointer">Kirim &amp; Selesaikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== TOAST NOTIFIKASI ==================== --}}
    @if(session('success'))
    <div id="toastSuccess" class="fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-[#0a2540] text-white px-5 py-3.5 rounded-2xl shadow-2xl transition-all duration-500 transform translate-y-10 opacity-0 max-w-sm border border-slate-700/50">
        <div class="w-7 h-7 bg-green-500 text-white rounded-full flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-check"></i></div>
        <p class="text-xs font-semibold tracking-wide pr-2">{{ session('success') }}</p>
        <button type="button" onclick="closeToast()" class="text-slate-400 hover:text-white transition ml-auto cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif
    @if(session('error'))
    <div id="toastError" class="fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-red-600 text-white px-5 py-3.5 rounded-2xl shadow-2xl transition-all duration-500 transform translate-y-10 opacity-0 max-w-sm">
        <div class="w-7 h-7 bg-white/20 text-white rounded-full flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-xmark"></i></div>
        <p class="text-xs font-semibold tracking-wide pr-2">{{ session('error') }}</p>
    </div>
    @endif

    <script>
        ['toastSuccess', 'toastError'].forEach(id => {
            const toast = document.getElementById(id);
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('translate-y-10', 'opacity-0');
                    toast.classList.add('translate-y-0', 'opacity-100');
                }, 100);
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-10');
                    setTimeout(() => toast.remove(), 500);
                }, 5000);
            }
        });
        function closeToast() {
            const toast = document.getElementById('toastSuccess');
            if (toast) {
                toast.classList.add('opacity-0', 'translate-y-10');
                setTimeout(() => toast.remove(), 500);
            }
        }

        // ===== MODAL KONFIRMASI MULAI KERJAKAN =====
        const mulaiModal = document.getElementById('mulaiModal');
        const formMulai = document.getElementById('formMulai');
        const mulaiSlaMap = {
            tinggi: { text: '1 Hari Kerja', class: 'text-red-700 bg-red-50 border-red-100' },
            sedang: { text: '3 Hari Kerja', class: 'text-amber-700 bg-amber-50 border-amber-100' },
            rendah: { text: '7 Hari Kerja', class: 'text-slate-700 bg-slate-100 border-slate-200' },
        };

        document.querySelectorAll('.btn-mulai').forEach(btn => {
            btn.addEventListener('click', () => {
                const prioritas = (btn.getAttribute('data-prioritas') || 'rendah').toLowerCase();
                const sla = mulaiSlaMap[prioritas] || mulaiSlaMap.rendah;

                document.getElementById('mulaiTicketCode').innerText = btn.getAttribute('data-code');
                document.getElementById('mulaiKategori').innerText = btn.getAttribute('data-kategori');
                document.getElementById('mulaiSlaText').innerText = sla.text;

                const badge = document.getElementById('mulaiPrioritasBadge');
                badge.className = 'inline-flex items-center gap-1.5 mt-1 text-xs font-bold px-2.5 py-1 rounded-lg border ' + sla.class;
                badge.innerText = btn.getAttribute('data-prioritas');

                formMulai.setAttribute('action', `/pj/ticket/${btn.getAttribute('data-id')}/terima`);

                mulaiModal.classList.remove('hidden');
                mulaiModal.classList.add('flex');
                setTimeout(() => {
                    mulaiModal.classList.remove('opacity-0');
                    mulaiModal.querySelector('.max-w-md').classList.remove('scale-95');
                }, 50);
            });
        });

        function closeMulaiModal() {
            mulaiModal.classList.add('opacity-0');
            mulaiModal.querySelector('.max-w-md').classList.add('scale-95');
            setTimeout(() => {
                mulaiModal.classList.add('hidden');
                mulaiModal.classList.remove('flex');
            }, 300);
        }

        const selesaikanModal = document.getElementById('selesaikanModal');
        const formSelesaikan = document.getElementById('formSelesaikan');
        const buktiFotoInput = document.getElementById('bukti_foto_input');

        function showAlertBox(alertId, message, isSuccess = false) {
            const alertBox = document.getElementById(alertId);
            alertBox.innerText = message;
            alertBox.classList.remove('hidden', 'bg-red-100', 'text-red-800', 'bg-green-100', 'text-green-800');
            alertBox.classList.add(isSuccess ? 'bg-green-100' : 'bg-red-100', isSuccess ? 'text-green-800' : 'text-red-800');
        }

        document.querySelectorAll('.btn-selesaikan').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('modalTicketCode').innerText = btn.getAttribute('data-code');
                formSelesaikan.setAttribute('action', `/pj/ticket/${btn.getAttribute('data-id')}/selesaikan`);
                document.getElementById('selesaikanAlert').classList.add('hidden');
                buktiFotoInput.value = '';

                selesaikanModal.classList.remove('hidden');
                selesaikanModal.classList.add('flex');
                setTimeout(() => {
                    selesaikanModal.classList.remove('opacity-0');
                    selesaikanModal.querySelector('.max-w-md').classList.remove('scale-95');
                }, 50);
            });
        });

        function closeSelesaikanModal() {
            selesaikanModal.classList.add('opacity-0');
            selesaikanModal.querySelector('.max-w-md').classList.add('scale-95');
            setTimeout(() => {
                selesaikanModal.classList.add('hidden');
                selesaikanModal.classList.remove('flex');
            }, 300);
        }

        formSelesaikan.addEventListener('submit', (e) => {
            if (!buktiFotoInput.files || buktiFotoInput.files.length === 0) {
                e.preventDefault();
                showAlertBox('selesaikanAlert', 'Foto bukti penyelesaian wajib diunggah sebelum tiket bisa ditandai selesai.', false);
                return;
            }
            const file = buktiFotoInput.files[0];
            if (file.size > 2 * 1024 * 1024) {
                e.preventDefault();
                showAlertBox('selesaikanAlert', 'Ukuran foto maksimal 2MB. Silakan kompres atau pilih foto lain.', false);
                return;
            }
        });
    
        const statusFilterBtn = document.getElementById('statusFilterBtn');
        const statusFilterMenu = document.getElementById('statusFilterMenu');
        const statusFilterArrow = document.getElementById('statusFilterArrow');

        statusFilterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            statusFilterMenu.classList.toggle('hidden');
            statusFilterArrow.classList.toggle('rotate-180');
        });

        document.querySelectorAll('.status-filter-item').forEach(item => {
            item.addEventListener('click', () => {
                const value = item.getAttribute('data-value');
                window.location.href = `{{ route('pj.dashboard') }}?status=${encodeURIComponent(value)}`;
            });
        });

        document.addEventListener('click', () => {
            statusFilterMenu.classList.add('hidden');
            statusFilterArrow.classList.remove('rotate-180');
        });

        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function() {
                window.location.href = this.getAttribute('data-url');
            });
        });

        document.querySelectorAll('.action-buttons').forEach(container => {
            container.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // ===== TOGGLE NOTIFIKASI BELL =====
        const notifBtn = document.getElementById('notifBtn');
        const notifMenu = document.getElementById('notifMenu');

        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            notifMenu.classList.add('hidden');
        });

        notifMenu.addEventListener('click', (e) => e.stopPropagation());

        // ===== TOGGLE GAUGE SLA =====
        const slaGaugeBtn = document.getElementById('slaGaugeBtn');
        const slaGaugeMenu = document.getElementById('slaGaugeMenu');

        slaGaugeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            slaGaugeMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            slaGaugeMenu.classList.add('hidden');
        });

        slaGaugeMenu.addEventListener('click', (e) => e.stopPropagation());
    </script>
</body>
</html>