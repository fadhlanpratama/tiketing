<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Detail Tiket User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
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
                        <span class="text-[9px] sm:text-[10px] text-amber-400 uppercase font-bold tracking-widest block">Portal Pengguna</span>
                    </div>
               </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('user.dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-200 flex items-center gap-2 border border-white/10 active:scale-95">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </header>

    {{-- 2. Main Content Container --}}
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
            
            {{-- KOLOM KIRI (2/3): Informational Content --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Card Deskripsi Utama --}}
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

                    {{-- Attribute Meta Grid --}}
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
                                <i class="fa-regular fa-image text-slate-400"></i> Foto Kerusakan Awal
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
                                <i class="fa-solid fa-circle-check"></i> Foto Hasil Perbaikan
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
                    @include('partials.ticket-chat-survey', ['chatRoute' => 'user.ticket.chat', 'isPj' => false])
                </div>
            </div>

            {{-- KOLOM KANAN (1/3): Sidebar Penanggung Jawab --}}
            <div class="space-y-6">

                {{-- Status PJ & Informasi Petugas --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4 w-full">
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
                            @elseif($ticket->pj_id)
                                <p class="text-xs font-bold text-slate-400 italic truncate"><i class="fa-solid fa-user-slash me-1"></i>Akun PJ Telah Dihapus</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Data penanggung jawab tidak lagi tersedia</p>
                            @else
                                <p class="text-xs font-bold text-slate-900 truncate">Belum Ditentukan</p>
                                <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Belum Ditentukan</p>
                            @endif
                        </div>
                    </div>

                    @if($ticket->status === 'Closed' && $ticket->closed_by === 'user')
                    <div class="p-3.5 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center gap-2 text-amber-800">
                        <i class="fa-solid fa-user-slash text-xs text-amber-600"></i>
                        <p class="text-xs font-semibold">Tiket telah ditutup oleh Anda</p>
                    </div>
                    @endif
                </div>

                {{-- ===== KARTU DAFTAR KOLABORATOR ===== --}}
                @if($ticket->collaborators->count() > 0)
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-3 w-full">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-indigo-400 rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Kolaborator Penanganan</h3>
                    </div>

                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        <i class="fa-solid fa-circle-info"></i> Petugas tambahan yang ikut membantu menangani tiket ini.
                    </p>

                    <div class="space-y-2">
                        @foreach($ticket->collaborators as $c)
                            <div class="flex items-center gap-2.5 p-3 bg-slate-50/80 rounded-2xl border border-slate-200/60">
                                <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $c->pj->nama_lengkap ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $c->pj->divisi ?? '-' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>

    </main>

</body>
</html>