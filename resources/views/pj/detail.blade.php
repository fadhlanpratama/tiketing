<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Detail Tiket PJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-100/70 min-h-screen font-sans text-slate-800 flex flex-col antialiased">

    {{-- 1. Header Utama ESDM --}}
    <header class="bg-[#0a2540] text-white sticky top-0 z-30 shadow-lg border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                    <div>
                        <h1 class="text-white font-black tracking-wider text-base sm:text-lg leading-tight">SISTEM TIKETING</h1>
                        <span class="text-[9px] sm:text-[10px] text-amber-400 uppercase font-bold tracking-widest block">Portal Penanggung Jawab</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('pj.dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-200 flex items-center gap-2 border border-white/10 active:scale-95">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </header>

    {{-- 2. Main Content Area --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Banner Card Identitas Tiket --}}
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2.5 flex-wrap">
                    {{-- Status Badge --}}
                    @if($ticket->status == 'Open')
                        <span class="inline-flex items-center gap-1.5 bg-amber-500/10 text-amber-700 border border-amber-500/20 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Open
                        </span>
                    @elseif($ticket->status == 'In Progress')
                        <span class="inline-flex items-center gap-1.5 bg-sky-500/10 text-sky-700 border border-sky-500/20 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span> In Progress
                        </span>
                    @elseif($ticket->status == 'Resolved')
                        <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Resolved
                        </span>
                    @elseif($ticket->status == 'Closed')
                        <span class="inline-flex items-center gap-1.5 bg-slate-200/80 text-slate-600 border border-slate-300/80 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Closed
                        </span>
                    @endif

                    @if(!$isOwner)
                        <span class="inline-flex items-center gap-1.5 bg-indigo-500/10 text-indigo-700 border border-indigo-500/20 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            <i class="fa-solid fa-people-arrows"></i> Kolaborator
                        </span>
                    @endif
                </div>

                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    #TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 flex items-center gap-1.5">
                    <i class="fa-regular fa-clock text-xs text-slate-400"></i> Diajukan pada {{ $ticket->created_at->format('d M Y, H:i') }} WIB
                </p>
            </div>

            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-amber-400 to-amber-500 text-[#0a2540] rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shrink-0 shadow-md shadow-amber-500/20 self-start sm:self-auto">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
        </div>

        {{-- Grid 2 Kolom --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full">
            
            {{-- KOLOM KIRI (2/3): Detail Deskripsi & Foto --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Card Informasi Masalah --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6 w-full">
                    <div class="border-b border-slate-100 pb-4 space-y-2">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold text-[#0a2540] uppercase tracking-wider bg-slate-100 px-3 py-1 rounded-lg border border-slate-200/60">
                            <i class="fa-solid fa-layer-group text-amber-500"></i> {{ $ticket->kategori }}
                        </span>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                            {{ $ticket->sub_kategori }}
                        </h2>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <div class="w-2.5 h-4 bg-amber-400 rounded-full"></div>
                            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Deskripsi Masalah</h3>
                        </div>
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/70 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line min-h-[100px]">
                            {{ $ticket->deskripsi_masalah }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                        <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-200/70 space-y-1">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Nomor BMN</p>
                            <p class="text-xs font-mono font-bold text-slate-800">{{ $ticket->nomor_bmn ?? '-' }}</p>
                        </div>
                        
                        <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-200/70 space-y-1">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Prioritas Tiket</p>
                            <p class="text-xs font-bold flex items-center gap-1.5 {{ (strtolower($ticket->prioritas) == 'high' || strtolower($ticket->prioritas) == 'tinggi') ? 'text-rose-600' : ((strtolower($ticket->prioritas) == 'medium' || strtolower($ticket->prioritas) == 'sedang') ? 'text-amber-600' : 'text-slate-700') }}">
                                <i class="fa-solid fa-circle text-[8px]"></i>
                                {{ ucfirst($ticket->prioritas ?? 'Low') }}
                            </p>
                        </div>

                        <div class="col-span-2 sm:col-span-1 bg-slate-50/80 p-4 rounded-2xl border border-slate-200/70 space-y-1">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Tanggal Selesai</p>
                            <p class="text-xs font-semibold text-slate-800">
                                {{ $ticket->tanggal_selesai ? $ticket->tanggal_selesai->format('d/m/Y H:i') : 'Dalam Proses' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card Lampiran Foto --}}
                @if($ticket->attachment_foto || $ticket->hasil_resolved_foto)
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-5 w-full">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-[#0a2540] rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Lampiran Dokumentasi</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($ticket->attachment_foto)
                        <div class="space-y-2">
                            <p class="text-[11px] font-bold text-slate-500 flex items-center gap-1.5">
                                <i class="fa-regular fa-image text-slate-400"></i> Foto Kerusakan (Pelapor)
                            </p>
                            <div class="border border-slate-200/80 rounded-2xl overflow-hidden bg-slate-50 group relative">
                                <img src="{{ asset('storage/' . $ticket->attachment_foto) }}" alt="Foto Kerusakan" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                                <a href="{{ asset('storage/' . $ticket->attachment_foto) }}" target="_blank" class="absolute inset-0 bg-[#0a2540]/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-bold transition duration-200 backdrop-blur-[2px]">
                                    <i class="fa-solid fa-expand mr-2"></i> Lihat Foto
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($ticket->hasil_resolved_foto)
                        <div class="space-y-2">
                            <p class="text-[11px] font-bold text-emerald-600 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check"></i> Bukti Penyelesaian
                            </p>
                            <div class="border border-emerald-200 rounded-2xl overflow-hidden bg-emerald-50/30 group relative">
                                <img src="{{ asset('storage/' . $ticket->hasil_resolved_foto) }}" alt="Foto Hasil" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                                <a href="{{ asset('storage/' . $ticket->hasil_resolved_foto) }}" target="_blank" class="absolute inset-0 bg-[#0a2540]/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-bold transition duration-200 backdrop-blur-[2px]">
                                    <i class="fa-solid fa-expand mr-2"></i> Lihat Foto
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Riwayat Komentar & Survei — dipindah ke kolom kiri, konsisten dengan halaman Admin --}}
                <div class="bg-white rounded-3xl p-2 shadow-sm border border-slate-200/80 overflow-hidden w-full">
                    @include('partials.ticket-chat-survey', ['chatRoute' => 'pj.ticket.chat', 'isPj' => true])
                </div>

            </div>

            {{-- KOLOM KANAN (1/3): Sidebar Gabungan --}}
            <div class="space-y-6">

                {{-- ===== KARTU GABUNGAN INFORMASI PELAPOR & SLA ===== --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-5 w-full">
                    
                    {{-- Sub-bagian 1: Informasi Pelapor --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <div class="w-2.5 h-5 bg-amber-400 rounded-full"></div>
                            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Informasi Pelapor</h3>
                        </div>

                        <div class="flex items-center gap-3.5 p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200/60">
                            <div class="w-11 h-11 rounded-xl bg-[#0a2540] text-amber-400 flex items-center justify-center font-black text-sm shrink-0 shadow-sm">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                                <p class="text-[11px] text-slate-500 font-semibold mt-0.5">{{ $ticket->pelapor->divisi ?? '-' }}</p>
                            </div>
                        </div>

                        @if($ticket->status === 'Closed' && $ticket->closed_by === 'user')
                        <div class="p-3.5 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center gap-2 text-amber-800">
                            <i class="fa-solid fa-user-slash text-xs text-amber-600"></i>
                            <p class="text-xs font-semibold">Dibatalkan / Ditutup oleh Pelapor</p>
                        </div>
                        @endif
                    </div>

                    {{-- Sub-bagian 2: Informasi SLA --}}
                    <div class="space-y-3 pt-2 border-t border-slate-100">
                        <div class="flex items-center justify-between pb-1">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-5 bg-[#0a2540] rounded-full"></div>
                                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Informasi SLA</h3>
                            </div>
                            
                            {{-- Status Badge SLA --}}
                            @php $slaBadge = $ticket->sla_badge; @endphp
                            @if($ticket->status === 'Closed' && $ticket->closed_by === 'user')
                                <span class="inline-flex items-center gap-1.5 text-slate-600 font-bold bg-slate-100 px-2.5 py-1 rounded-lg text-[10px] border border-slate-200">
                                    <i class="fa-solid fa-ban"></i>Dibatalkan
                                </span>
                            @elseif(!$slaBadge)
                                <span class="text-slate-400 text-xs italic">Belum Diproses</span>
                            @elseif($slaBadge['terlambat'])
                                <span class="inline-flex items-center gap-1.5 text-red-700 font-bold bg-red-50 px-2.5 py-1 rounded-lg text-[10px] border border-red-100">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $slaBadge['sedang_proses'] ? 'Lewat SLA ' : 'Terlambat ' }}{{ $slaBadge['label'] }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-green-700 font-bold bg-green-50 px-2.5 py-1 rounded-lg text-[10px] border border-green-100">
                                    <i class="fa-solid fa-circle-check"></i>
                                    {{ $slaBadge['sedang_proses'] ? 'Dalam SLA' : 'Tepat Waktu' }}
                                </span>
                            @endif
                        </div>

                        <div class="space-y-2.5 text-xs">
                            {{-- Waktu Pertama Kali Ditekan ("Mulai Kerjakan") --}}
                            <div class="bg-slate-50 border border-slate-100 p-3 rounded-2xl">
                                <p class="text-slate-400 font-extrabold uppercase text-[10px] tracking-wider">Waktu Mulai Dikerjakan</p>
                                @if($ticket->status === 'Closed' && $ticket->closed_by === 'user')
                                    @if($ticket->waktu_mulai_dikerjakan)
                                        <p class="text-slate-500 font-semibold text-xs mt-0.5 flex items-center gap-1.5">
                                            <i class="fa-solid fa-ban text-slate-400"></i>
                                            {{ \Carbon\Carbon::parse($ticket->waktu_mulai_dikerjakan)->format('d M Y, H:i') }} WIB
                                            <span class="italic text-slate-400">(Dibatalkan)</span>
                                        </p>
                                    @else
                                        <p class="text-slate-400 italic text-xs mt-0.5 flex items-center gap-1.5">
                                            <i class="fa-solid fa-ban"></i> Dibatalkan sebelum dikerjakan
                                        </p>
                                    @endif
                                @elseif($ticket->waktu_mulai_dikerjakan)
                                    <p class="text-slate-800 font-bold text-xs mt-0.5 flex items-center gap-1.5">
                                        <i class="fa-regular fa-calendar-check text-blue-500"></i>
                                        {{ \Carbon\Carbon::parse($ticket->waktu_mulai_dikerjakan)->format('d M Y, H:i') }} WIB
                                    </p>
                                @else
                                    <p class="text-amber-600 font-medium italic text-xs mt-0.5 flex items-center gap-1.5">
                                        <i class="fa-solid fa-hourglass-start"></i> Menunggu "Mulai Kerjakan"
                                    </p>
                                @endif
                            </div>

                            {{-- Target SLA Berdasarkan Prioritas --}}
                            <div class="bg-slate-50 border border-slate-100 p-3 rounded-2xl">
                                <p class="text-slate-400 font-extrabold uppercase text-[10px] tracking-wider">Target SLA ({{ ucfirst($ticket->prioritas ?? 'Rendah') }})</p>
                                <p class="text-slate-800 font-bold text-xs mt-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-stopwatch text-amber-500"></i>
                                    @if($ticket->status === 'Closed' && $ticket->closed_by === 'user')
                                        <span class="text-slate-400 italic font-normal">Tidak Dihitung (Dibatalkan)</span>
                                    @else
                                        {{ $targetSlaText ?? '7 Hari Kerja' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== KARTU TIMWORK / KOLABORATOR ===== --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4 w-full">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-indigo-500 rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Tim Penanganan Tiket</h3>
                    </div>

                    @if(session('success'))
                        <div class="p-3 rounded-xl text-xs font-semibold bg-emerald-100 text-emerald-800">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="p-3 rounded-xl text-xs font-semibold bg-rose-100 text-rose-800">{{ session('error') }}</div>
                    @endif

                    {{-- Daftar Anggota Tim (PJ Utama & Kolaborator) --}}
                    <div class="space-y-2">
                        
                        {{-- 1. PJ Utama --}}
                        <div class="space-y-1">
                            <span class="text-[9px] font-black uppercase text-amber-600 tracking-wider">PJ Utama (Penanggung Jawab)</span>
                            <div class="flex items-center justify-between gap-2 p-3 bg-amber-500/5 rounded-2xl border border-amber-500/20">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-[#0a2540] text-amber-400 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i class="fa-solid fa-user-shield"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-900 truncate">{{ optional($ticket->pj)->nama_lengkap ?? $ticket->penanggung_jawab ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-500">{{ optional($ticket->pj)->divisi ?? '-' }}</p>
                                    </div>
                                </div>
                                <span class="bg-amber-100 text-amber-800 text-[9px] font-bold px-2 py-0.5 rounded-md border border-amber-200 shrink-0">PJ Utama</span>
                            </div>
                        </div>

                        {{-- 2. Daftar Kolaborator --}}
                        <div class="space-y-1 pt-2">
                            <span class="text-[9px] font-black uppercase text-indigo-600 tracking-wider">Kolaborator Tambahan</span>
                            
                            @forelse($ticket->collaborators as $c)
                                <div class="flex items-center justify-between gap-2 p-3 bg-slate-50/80 rounded-2xl border border-slate-200/60">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-800 truncate">{{ $c->pj->nama_lengkap ?? '-' }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $c->pj->divisi ?? '-' }}</p>
                                        </div>
                                    </div>

                                    @if($isOwner && in_array($ticket->status, ['Open', 'In Progress']))
                                        {{-- Hidden Form Hapus Kolaborator --}}
                                        <form id="formRemoveCollab-{{ $c->id }}" action="{{ route('pj.ticket.collaborator.remove', [$ticket->id, $c->id]) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" 
                                                onclick="openRemoveCollabModal({{ $c->id }}, '{{ addslashes($c->pj->nama_lengkap ?? 'Kolaborator') }}')" 
                                                class="text-rose-400 hover:text-rose-600 transition w-7 h-7 flex items-center justify-center rounded-lg hover:bg-rose-50 active:scale-95">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic text-center py-2 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                    Belum ada kolaborator tambahan.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Form Tambah Kolaborator 2-Step (Divisi -> Pilih PJ + Indikator SLA) --}}
                    @if($isOwner)
                        @php $bisaKelolaKolaborator = in_array($ticket->status, ['Open', 'In Progress']); @endphp

                        @if($bisaKelolaKolaborator)
                            @if($availablePjs->count() > 0)
                            <form action="{{ route('pj.ticket.invite', $ticket->id) }}" method="POST" class="pt-3 border-t border-slate-100 space-y-3">
                                @csrf
                                <input type="hidden" name="collaborator_id" id="inputCollabPjId" required>

                                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Undang Kolaborator</span>

                                <!-- Step 1: Dropdown Pilih Divisi -->
                                <div class="space-y-1 relative custom-dropdown" id="dropdownDivisi">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase">1. Pilih Divisi</label>
                                    <button type="button" class="dropdown-btn w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-700 font-medium flex items-center justify-between transition focus:bg-white focus:ring-2 focus:ring-indigo-400 outline-none cursor-pointer">
                                        <span class="dropdown-label truncate">-- Pilih Divisi --</span>
                                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                                    </button>
                                    <div class="dropdown-menu hidden absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-100 rounded-2xl shadow-xl p-1.5 space-y-0.5 max-h-48 overflow-y-auto no-scrollbar">
                                        @php
                                            $daftarDivisiPj = $availablePjs->pluck('divisi')->unique()->filter();
                                        @endphp
                                        @forelse($daftarDivisiPj as $div)
                                            <div data-value="{{ $div }}" class="dropdown-item-divisi w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer flex items-center justify-between">
                                                <span>{{ $div }}</span>
                                            </div>
                                        @empty
                                            <div class="px-3 py-2 text-xs text-slate-400">Tidak ada divisi tersedia</div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Step 2: Dropdown Pilih PJ dengan Indikator Speedometer SLA -->
                                <div class="space-y-1 relative custom-dropdown" id="dropdownPj">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase">2. Pilih PJ / Teknisi</label>
                                    <button type="button" id="btnPj" disabled class="dropdown-btn w-full bg-slate-100 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-400 font-medium flex items-center justify-between transition outline-none cursor-not-allowed">
                                        <span class="dropdown-label truncate">-- Pilih Divisi Dahulu --</span>
                                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                                    </button>
                                    <div class="dropdown-menu hidden absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-100 rounded-2xl shadow-xl p-1.5 space-y-0.5 max-h-56 overflow-y-auto no-scrollbar" id="menuPj">
                                        @foreach($availablePjs as $pj)
                                            @php
                                                $stats = $pj->sla_stats;
                                                $needleDeg = $stats['sla_needle_deg'] ?? 0;
                                            @endphp
                                            <div data-value="{{ $pj->id }}"
                                                 data-divisi="{{ $pj->divisi }}"
                                                 data-nama="{{ $pj->nama_lengkap }}"
                                                 class="dropdown-item-pj hidden w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer flex items-center justify-between gap-2">
                                                <span class="truncate">{{ $pj->nama_lengkap }}</span>
                                                
                                                {{-- Indikator Speedometer Mini (Jarum SLA) --}}
                                                <div class="shrink-0 flex items-center gap-1.5 bg-rose-50 border border-rose-100 px-2 py-1 rounded-lg">
                                                    <svg width="22" height="14" viewBox="0 0 64 40" class="shrink-0">
                                                        <path d="M4,32 A28,28 0 0 1 18,7.75" fill="none" stroke="#22c55e" stroke-width="7" stroke-linecap="round"/>
                                                        <path d="M18,7.75 A28,28 0 0 1 46,7.75" fill="none" stroke="#eab308" stroke-width="7" stroke-linecap="round"/>
                                                        <path d="M46,7.75 A28,28 0 0 1 60,32" fill="none" stroke="#ef4444" stroke-width="7" stroke-linecap="round"/>
                                                        <line x1="32" y1="32" x2="32" y2="10" stroke="#1e293b" stroke-width="4.5" stroke-linecap="round" transform="rotate({{ $needleDeg }}, 32, 32)"/>
                                                        <circle cx="32" cy="32" r="4.5" fill="#1e293b"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition active:scale-95 shadow-sm mt-1">
                                    <i class="fa-solid fa-user-plus me-1"></i> Undang Kolaborator
                                </button>
                            </form>
                            @else
                                <p class="text-[11px] text-slate-400 italic pt-3 border-t border-slate-100">Semua PJ yang tersedia sudah menjadi kolaborator.</p>
                            @endif

                            <p class="text-[10px] text-slate-400 leading-relaxed pt-1">
                                <i class="fa-solid fa-circle-info"></i> Kolaborator dapat melihat detail tiket dan berdiskusi. Tanggung jawab SLA tetap berada pada PJ Utama.
                            </p>
                        @else
                            <p class="text-[11px] text-slate-400 italic pt-3 border-t border-slate-100">
                                <i class="fa-solid fa-lock"></i> Tiket sudah {{ strtolower($ticket->status) }}. Kolaborator tidak dapat diubah.
                            </p>
                        @endif
                    @else
                        <div class="p-3 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-start gap-2">
                            <i class="fa-solid fa-eye text-indigo-500 text-xs mt-0.5"></i>
                            <p class="text-[11px] text-indigo-700 leading-relaxed">
                                Anda bergabung dalam tim penanganan tiket ini sebagai <span class="font-bold">Kolaborator</span>.
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </main>

    {{-- MODAL POP-UP KONFIRMASI HAPUS KOLABORATOR --}}
    <div id="removeCollabModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 transition-all duration-300">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm sm:max-w-md w-full shadow-2xl border border-slate-100 text-center space-y-6 transform scale-95 transition-transform duration-200" id="collabModalCard">
            
            {{-- Icon Peringatan Merah (Sesuai Gambar) --}}
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto text-2xl border border-rose-100 shadow-inner">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            {{-- Teks Informasi Modal --}}
            <div class="space-y-2">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Apakah Anda Yakin?</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Kolaborator <span id="collabNameText" class="font-bold text-slate-800"></span> akan dihapus dari tim penanganan tiket ini.
                </p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="button" onclick="closeRemoveCollabModal()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-2xl text-xs transition cursor-pointer active:scale-95">
                    Batal
                </button>
                <button type="button" onclick="confirmRemoveCollab()" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 rounded-2xl text-xs transition shadow-md shadow-rose-600/20 cursor-pointer active:scale-95">
                    Ya, Hapus
                </button>
            </div>

        </div>
    </div>

    <script>
        // --- JS 2-Step Dropdown Divisi & PJ (Dengan Indikator SLA) ---
        const dropdownDivisi = document.getElementById('dropdownDivisi');
        const dropdownPj = document.getElementById('dropdownPj');

        if (dropdownDivisi && dropdownPj) {
            const btnDivisi = dropdownDivisi.querySelector('.dropdown-btn');
            const menuDivisi = dropdownDivisi.querySelector('.dropdown-menu');
            const labelDivisi = dropdownDivisi.querySelector('.dropdown-label');
            const arrowDivisi = btnDivisi.querySelector('.fa-chevron-down');

            const btnPj = dropdownPj.querySelector('.dropdown-btn');
            const menuPj = dropdownPj.querySelector('.dropdown-menu');
            const labelPj = dropdownPj.querySelector('.dropdown-label');
            const arrowPj = btnPj.querySelector('.fa-chevron-down');

            const inputCollabPjId = document.getElementById('inputCollabPjId');
            const itemsPj = dropdownPj.querySelectorAll('.dropdown-item-pj');

            btnDivisi.addEventListener('click', (e) => {
                e.stopPropagation();
                menuPj.classList.add('hidden');
                arrowPj.classList.remove('rotate-180');

                menuDivisi.classList.toggle('hidden');
                arrowDivisi.classList.toggle('rotate-180');
            });

            btnPj.addEventListener('click', (e) => {
                e.stopPropagation();
                if (btnPj.disabled) return;

                menuDivisi.classList.add('hidden');
                arrowDivisi.classList.remove('rotate-180');

                menuPj.classList.toggle('hidden');
                arrowPj.classList.toggle('rotate-180');
            });

            dropdownDivisi.querySelectorAll('.dropdown-item-divisi').forEach(item => {
                item.addEventListener('click', () => {
                    const selectedDivisi = item.getAttribute('data-value');
                    labelDivisi.innerText = selectedDivisi;

                    menuDivisi.classList.add('hidden');
                    arrowDivisi.classList.remove('rotate-180');

                    inputCollabPjId.value = '';
                    labelPj.innerText = '-- Pilih PJ / Teknisi --';

                    btnPj.disabled = false;
                    btnPj.classList.remove('bg-slate-100', 'cursor-not-allowed', 'text-slate-400');
                    btnPj.classList.add('bg-slate-50', 'cursor-pointer', 'text-slate-700');

                    itemsPj.forEach(pjItem => {
                        if (pjItem.getAttribute('data-divisi') === selectedDivisi) {
                            pjItem.classList.remove('hidden');
                        } else {
                            pjItem.classList.add('hidden');
                        }
                    });
                });
            });

            itemsPj.forEach(item => {
                item.addEventListener('click', () => {
                    const pjId = item.getAttribute('data-value');
                    const pjNama = item.getAttribute('data-nama');

                    inputCollabPjId.value = pjId;
                    labelPj.innerText = pjNama;

                    menuPj.classList.add('hidden');
                    arrowPj.classList.remove('rotate-180');
                });
            });

            document.addEventListener('click', () => {
                menuDivisi.classList.add('hidden');
                arrowDivisi.classList.remove('rotate-180');
                menuPj.classList.add('hidden');
                arrowPj.classList.remove('rotate-180');
            });
        }

        // --- JS Modal Konfirmasi Hapus Kolaborator ---
        const collabModal = document.getElementById('removeCollabModal');
        const collabModalCard = document.getElementById('collabModalCard');
        let selectedCollabFormId = null;

        function openRemoveCollabModal(collabId, name) {
            selectedCollabFormId = 'formRemoveCollab-' + collabId;
            document.getElementById('collabNameText').innerText = name;
            
            collabModal.classList.remove('hidden');
            setTimeout(() => {
                collabModalCard.classList.remove('scale-95');
                collabModalCard.classList.add('scale-100');
            }, 10);
        }

        function closeRemoveCollabModal() {
            collabModalCard.classList.remove('scale-100');
            collabModalCard.classList.add('scale-95');
            setTimeout(() => {
                collabModal.classList.add('hidden');
            }, 150);
        }

        function confirmRemoveCollab() {
            if (selectedCollabFormId) {
                const targetForm = document.getElementById(selectedCollabFormId);
                if (targetForm) targetForm.submit();
            }
        }

        // Close modal jika klik backdrop
        collabModal.addEventListener('click', (e) => {
            if (e.target === collabModal) {
                closeRemoveCollabModal();
            }
        });
    </script>
</body>
</html>