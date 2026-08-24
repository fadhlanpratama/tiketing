<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Detail Tiket Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-100/70 min-h-screen font-sans text-slate-800 flex flex-col antialiased">

    {{-- Header --}}
    <header class="bg-[#0a2540] text-white sticky top-0 z-30 shadow-lg border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('image/esdm.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                <div>
                    <h1 class="text-white font-black tracking-wider text-base sm:text-lg leading-tight">SISTEM TIKETING</h1>
                    <span class="text-[9px] sm:text-[10px] text-amber-400 uppercase font-bold tracking-widest block">Portal Admin</span>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-2 border border-white/10 active:scale-95">
                <i class="fa-solid fa-arrow-left text-xs"></i> <span>Kembali</span>
            </a>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i> <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Banner Identitas Tiket --}}
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <div class="space-y-1.5">
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
                        @if($ticket->closed_by === 'user') · Dibatalkan Pelapor @endif
                    </span>
                @endif

                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    #TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 flex items-center gap-1.5">
                    <i class="fa-regular fa-clock text-xs text-slate-400"></i> Diajukan pada {{ $ticket->created_at->format('d M Y, H:i') }} WIB
                </p>
            </div>
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-amber-400 to-amber-500 text-[#0a2540] rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shrink-0 shadow-md shadow-amber-500/20">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full">

            {{-- KOLOM KIRI --}}
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6 w-full">
                    <div class="border-b border-slate-100 pb-4 space-y-2">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold text-[#0a2540] uppercase tracking-wider bg-slate-100 px-3 py-1 rounded-lg border border-slate-200/60">
                            <i class="fa-solid fa-layer-group text-amber-500"></i> {{ $ticket->kategori }}
                        </span>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">{{ $ticket->sub_kategori }}</h2>
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
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Prioritas</p>
                            <p class="text-xs font-bold text-slate-700">{{ $ticket->prioritas ?? '-' }}</p>
                        </div>
                        <div class="col-span-2 sm:col-span-1 bg-slate-50/80 p-4 rounded-2xl border border-slate-200/70 space-y-1">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Tanggal Selesai</p>
                            <p class="text-xs font-semibold text-slate-800">
                                {{ $ticket->tanggal_selesai ? $ticket->tanggal_selesai->format('d/m/Y H:i') : 'Dalam Proses' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Lampiran Foto --}}
                @if($ticket->attachment_foto || $ticket->hasil_resolved_foto)
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-5 w-full">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-[#0a2540] rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Lampiran Dokumentasi</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($ticket->attachment_foto)
                        <div class="space-y-2">
                            <p class="text-[11px] font-bold text-slate-500">Foto Kerusakan (Pelapor)</p>
                            <a href="{{ asset('storage/' . $ticket->attachment_foto) }}" target="_blank" class="block border border-slate-200/80 rounded-2xl overflow-hidden bg-slate-50">
                                <img src="{{ asset('storage/' . $ticket->attachment_foto) }}" class="w-full h-48 object-cover">
                            </a>
                        </div>
                        @endif
                        @if($ticket->hasil_resolved_foto)
                        <div class="space-y-2">
                            <p class="text-[11px] font-bold text-emerald-600">Bukti Penyelesaian</p>
                            <a href="{{ asset('storage/' . $ticket->hasil_resolved_foto) }}" target="_blank" class="block border border-emerald-200 rounded-2xl overflow-hidden bg-emerald-50/30">
                                <img src="{{ asset('storage/' . $ticket->hasil_resolved_foto) }}" class="w-full h-48 object-cover">
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Riwayat Chat (Read-only) --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-4 w-full">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-amber-400 rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Riwayat Komentar</h3>
                    </div>

                    <div class="space-y-3 max-h-96 overflow-y-auto pr-1 no-scrollbar">
                        @forelse($ticket->messages as $m)
                        <div class="flex gap-3 {{ $m->sender_type === 'user' ? '' : 'flex-row-reverse' }}">
                            <div class="w-8 h-8 rounded-full {{ $m->sender_type === 'user' ? 'bg-slate-200 text-slate-600' : 'bg-[#0a2540] text-amber-400' }} flex items-center justify-center text-[10px] font-bold shrink-0">
                                {{ strtoupper(substr($m->sender_nama ?? '?', 0, 1)) }}
                            </div>
                            <div class="max-w-[75%] {{ $m->sender_type === 'user' ? 'bg-slate-100' : 'bg-amber-50' }} rounded-2xl px-4 py-2.5 space-y-1">
                                <p class="text-[10px] font-bold text-slate-500">{{ $m->sender_nama }}</p>
                                @if($m->pesan)
                                    <p class="text-xs text-slate-700 whitespace-pre-line">{{ $m->pesan }}</p>
                                @endif
                                @if($m->foto)
                                    <a href="{{ asset('storage/' . $m->foto) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $m->foto) }}" class="w-32 h-32 object-cover rounded-lg mt-1">
                                    </a>
                                @endif
                                <p class="text-[9px] text-slate-400">{{ $m->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 text-center py-6">Belum ada komentar pada tiket ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="space-y-6">

                {{-- 1. Info Pelapor --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-3 w-full">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-amber-400 rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Informasi Pelapor</h3>
                    </div>
                    <div class="flex items-center gap-3.5 p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200/60">
                        <div class="w-11 h-11 rounded-xl bg-[#0a2540] text-amber-400 flex items-center justify-center font-black text-sm shrink-0">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="min-w-0">
                            @if($ticket->pelapor)
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $ticket->pelapor->nama_lengkap }}</p>
                                <p class="text-[11px] text-slate-500 font-semibold mt-0.5">{{ $ticket->pelapor->divisi ?? '-' }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5"><i class="fa-regular fa-envelope me-1"></i>{{ $ticket->pelapor->email ?? '-' }}</p>
                            @else
                                <p class="text-xs font-bold text-slate-400 italic truncate"><i class="fa-solid fa-user-slash me-1"></i>Akun Pelapor Telah Dihapus</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Data pelapor tidak lagi tersedia</p>
                            @endif
                        </div>
                    </div>

                    @if($ticket->status === 'Closed' && $ticket->closed_by === 'user')
                    <div class="p-3.5 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center gap-2 text-amber-800">
                        <i class="fa-solid fa-user-slash text-xs text-amber-600"></i>
                        <p class="text-xs font-semibold">Dibatalkan / Ditutup oleh Pelapor</p>
                    </div>
                    @endif
                </div>

                {{-- 2. KARTU GABUNGAN: PENANGGUNG JAWAB & INFORMASI SLA --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-5 w-full">
                    
                    {{-- Sub-bagian A: Penanggung Jawab --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <div class="w-2.5 h-5 bg-amber-400 rounded-full"></div>
                            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Penanggung Jawab</h3>
                        </div>

                        <div class="flex items-center gap-3.5 p-3.5 bg-slate-50/80 rounded-2xl border border-slate-200/60">
                            <div class="w-11 h-11 rounded-xl bg-[#0a2540] text-amber-400 flex items-center justify-center font-black text-sm shrink-0 shadow-sm">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div class="min-w-0">
                                @if($ticket->pj)
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $ticket->pj->nama_lengkap }}</p>
                                    <p class="text-[11px] text-slate-500 font-semibold mt-0.5">{{ $ticket->pj->divisi ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5"><i class="fa-regular fa-envelope me-1"></i>{{ $ticket->pj->email ?? '-' }}</p>
                                @elseif($ticket->pj_id)
                                    <p class="text-xs font-bold text-slate-400 italic truncate"><i class="fa-solid fa-user-slash me-1"></i>Akun PJ Telah Dihapus</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Data penanggung jawab tidak lagi tersedia</p>
                                @else
                                    <p class="text-xs font-bold text-slate-900 truncate">Belum Ditentukan</p>
                                    <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Belum Ditentukan</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Sub-bagian B: Informasi SLA --}}
                    <div class="space-y-3 pt-2 border-t border-slate-100">
                        <div class="flex items-center justify-between pb-1">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-5 bg-[#0a2540] rounded-full"></div>
                                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Informasi SLA</h3>
                            </div>
                            
                            {{-- Status Badge SLA Dinamis --}}
                            @php $slaBadge = $ticket->sla_badge; @endphp
                            @if($ticket->status === 'Closed' && $ticket->closed_by === 'user')
                                <span class="inline-flex items-center gap-1.5 text-slate-600 font-bold bg-slate-100 px-2.5 py-1 rounded-lg text-[10px] border border-slate-200">
                                    <i class="fa-solid fa-ban"></i> Dibatalkan
                                </span>
                            @elseif(!$ticket->pj_id)
                                <span class="inline-flex items-center gap-1.5 text-amber-700 font-bold bg-amber-50 px-2.5 py-1 rounded-lg text-[10px] border border-amber-200/60">
                                    <i class="fa-solid fa-user-clock"></i> Belum Ada PJ
                                </span>
                            @elseif(!$ticket->pj)
                                <span class="inline-flex items-center gap-1.5 text-slate-500 font-bold bg-slate-100 px-2.5 py-1 rounded-lg text-[10px] border border-slate-200">
                                    <i class="fa-solid fa-user-slash"></i> PJ Dihapus
                                </span>
                            @elseif(!$ticket->waktu_mulai_dikerjakan)
                                <span class="inline-flex items-center gap-1.5 text-slate-500 font-bold bg-slate-100 px-2.5 py-1 rounded-lg text-[10px] border border-slate-200">
                                    <i class="fa-solid fa-hourglass-half"></i> Belum Diproses PJ
                                </span>
                            @elseif($slaBadge && $slaBadge['terlambat'])
                                <span class="inline-flex items-center gap-1.5 text-red-700 font-bold bg-red-50 px-2.5 py-1 rounded-lg text-[10px] border border-red-100">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    {{ $slaBadge['sedang_proses'] ? 'Lewat SLA ' : 'Terlambat ' }}{{ $slaBadge['label'] }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-green-700 font-bold bg-green-50 px-2.5 py-1 rounded-lg text-[10px] border border-green-100">
                                    <i class="fa-solid fa-circle-check"></i>
                                    {{ ($slaBadge && $slaBadge['sedang_proses']) ? 'Dalam SLA' : 'Tepat Waktu' }}
                                </span>
                            @endif
                        </div>

                        <div class="space-y-2.5 text-xs">
                            {{-- Waktu Mulai Dikerjakan --}}
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
                                @elseif(!$ticket->pj_id)
                                    <p class="text-amber-600 font-medium italic text-xs mt-0.5 flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-plus"></i> Menunggu Penunjukan PJ
                                    </p>
                                @else
                                    <p class="text-sky-600 font-medium italic text-xs mt-0.5 flex items-center gap-1.5">
                                        <i class="fa-solid fa-hourglass-start"></i> Menunggu Dikerjakan PJ
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

                {{-- 3. KARTU DAFTAR KOLABORATOR (READ-ONLY) --}}
                @if($ticket->collaborators->count() > 0)
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-3 w-full">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-indigo-400 rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Kolaborator Penanganan</h3>
                    </div>

                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        <i class="fa-solid fa-circle-info"></i> Petugas tambahan yang diundang oleh PJ utama untuk membantu menangani tiket ini. Tanggung jawab SLA tetap berada pada PJ utama.
                    </p>

                    <div class="space-y-2">
                        @foreach($ticket->collaborators as $c)
                            <div class="flex items-center gap-2.5 p-3 bg-slate-50/80 rounded-2xl border border-slate-200/60">
                                <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="min-w-0">
                                    @if($c->pj)
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $c->pj->nama_lengkap }}</p>
                                        <p class="text-[10px] text-slate-400 truncate">{{ $c->pj->divisi ?? '-' }}</p>
                                    @else
                                        <p class="text-xs font-bold text-slate-400 italic truncate"><i class="fa-solid fa-user-slash me-1"></i>Akun PJ Telah Dihapus</p>
                                        <p class="text-[10px] text-slate-400 truncate">Data kolaborator tidak tersedia</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- 4. Aksi Admin --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4 w-full">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-[#0a2540] rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Aksi Admin</h3>
                    </div>

                    @if($ticket->status === 'Open' && !$ticket->pj_id)
                        <form action="{{ route('admin.ticket.assign', $ticket->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="penanggung_jawab" id="inputPjId" required>

                            <!-- Dropdown 1: Pilih Divisi -->
                            <div class="space-y-1 relative custom-dropdown" id="dropdownDivisi">
                                <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">1. Pilih Divisi PJ</label>
                                <button type="button" class="dropdown-btn w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-700 font-medium flex items-center justify-between transition focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none cursor-pointer">
                                    <span class="dropdown-label truncate">-- Pilih Divisi --</span>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                                </button>
                                <div class="dropdown-menu hidden absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-100 rounded-2xl shadow-xl p-1.5 space-y-0.5 max-h-48 overflow-y-auto no-scrollbar">
                                    @php
                                        $daftarDivisiPj = $activePjs->pluck('divisi')->unique()->filter();
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

                            <!-- Dropdown 2: Pilih PJ / Teknisi dengan Indikator Jarum -->
                            <div class="space-y-1 relative custom-dropdown" id="dropdownPj">
                                <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">2. Pilih PJ / Teknisi</label>
                                <button type="button" id="btnPj" disabled class="dropdown-btn w-full bg-slate-100 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-400 font-medium flex items-center justify-between transition outline-none cursor-not-allowed">
                                    <span class="dropdown-label truncate">-- Pilih Divisi Dahulu --</span>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                                </button>
                                <div class="dropdown-menu hidden absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-100 rounded-2xl shadow-xl p-1.5 space-y-0.5 max-h-56 overflow-y-auto no-scrollbar" id="menuPj">
                                    @foreach($activePjs as $pj)
                                        @php
                                            $stats = $pj->sla_stats;
                                            $needleDeg = $stats['sla_needle_deg'] ?? 0;
                                        @endphp
                                        <div data-value="{{ $pj->id }}"
                                             data-divisi="{{ $pj->divisi }}"
                                             data-nama="{{ $pj->nama_lengkap }}"
                                             class="dropdown-item-pj hidden w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer flex items-center justify-between gap-2">
                                            <span class="truncate">{{ $pj->nama_lengkap }}</span>
                                            
                                            {{-- Indikator Speedometer Mini (Jarum) --}}
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

                            <button type="submit" class="w-full bg-[#0a2540] hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-xl transition text-xs shadow-sm cursor-pointer mt-2">
                                <i class="fa-solid fa-user-check me-1"></i> Tunjuk PJ
                            </button>
                        </form>
                    @elseif($ticket->status === 'Resolved')
                        {{-- Form Close Tiket yang memicu Modal Custom --}}
                        <form id="formCloseTicket" action="{{ route('admin.ticket.close', $ticket->id) }}" method="POST">
                            @csrf
                            <button type="button" onclick="openCloseModal()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-xl transition text-xs shadow-sm cursor-pointer flex items-center justify-center gap-1.5 active:scale-95">
                                <i class="fa-solid fa-lock"></i> Ubah Ke Closed
                            </button>
                        </form>
                    @else
                        <p class="text-xs text-slate-400 italic">Tidak ada aksi tersedia untuk status tiket ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </main>

    {{-- MODAL KONFIRMASI CLOSE TIKET --}}
    <div id="closeTicketModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 transition-all duration-300">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm sm:max-w-md w-full shadow-2xl border border-slate-100 text-center space-y-6 transform scale-95 transition-transform duration-200" id="modalCard">
            
            {{-- Icon Peringatan / Konfirmasi --}}
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto text-2xl border border-emerald-100/60 shadow-inner">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>

            {{-- Teks Informasi Modal --}}
            <div class="space-y-2">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Apakah Anda Yakin?</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Tiket <span class="font-bold text-slate-800">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span> akan diubah statusnya menjadi <span class="font-bold text-emerald-600">Closed</span>. Tindakan ini menandakan penanganan tiket telah selesai sepenuhnya.
                </p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="button" onclick="closeCloseModal()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-2xl text-xs transition cursor-pointer active:scale-95">
                    Batal
                </button>
                <button type="button" onclick="confirmCloseTicket()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-2xl text-xs transition shadow-md shadow-emerald-600/20 cursor-pointer active:scale-95">
                    Ya, Tutup Tiket
                </button>
            </div>

        </div>
    </div>

    <script>
        // --- JS Modal Konfirmasi Close Tiket ---
        const modal = document.getElementById('closeTicketModal');
        const modalCard = document.getElementById('modalCard');
        const formCloseTicket = document.getElementById('formCloseTicket');

        function openCloseModal() {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalCard.classList.remove('scale-95');
                modalCard.classList.add('scale-100');
            }, 10);
        }

        function closeCloseModal() {
            modalCard.classList.remove('scale-100');
            modalCard.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        function confirmCloseTicket() {
            if (formCloseTicket) {
                formCloseTicket.submit();
            }
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeCloseModal();
            }
        });

        // --- JS Dropdown Divisi & PJ ---
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

            const inputPjId = document.getElementById('inputPjId');
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

                    inputPjId.value = '';
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

                    inputPjId.value = pjId;
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
    </script>
</body>
</html>