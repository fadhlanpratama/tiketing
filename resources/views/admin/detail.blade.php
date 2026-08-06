<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Detail Tiket Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            <div class="bg-green-100 border border-green-200 text-green-800 p-4 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-600 text-sm"></i> <span>{{ session('success') }}</span>
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
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">PJ Teknisi</p>
                            <p class="text-xs font-semibold text-slate-800">{{ optional($ticket->pj)->nama_lengkap ?? $ticket->penanggung_jawab ?? 'Belum ditunjuk' }}</p>
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
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Riwayat Diskusi (Read-only)</h3>
                    </div>

                    <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
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
                        <p class="text-xs text-slate-400 text-center py-6">Belum ada diskusi pada tiket ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="space-y-6">

                {{-- Info Pelapor --}}
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
                            <p class="text-xs font-bold text-slate-900 truncate">{{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                            <p class="text-[11px] text-slate-500 font-semibold mt-0.5">{{ $ticket->pelapor->divisi ?? '-' }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">{{ $ticket->pelapor->email ?? '-' }}</p>
                        </div>
                    </div>

                    @if($ticket->status === 'Closed' && $ticket->closed_by === 'user')
                    <div class="p-3.5 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center gap-2 text-amber-800">
                        <i class="fa-solid fa-user-slash text-xs text-amber-600"></i>
                        <p class="text-xs font-semibold">Dibatalkan / Ditutup oleh Pelapor</p>
                    </div>
                    @endif
                </div>

                {{-- Aksi Admin --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4 w-full">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-[#0a2540] rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Aksi Admin</h3>
                    </div>

                    @if($ticket->status === 'Open' && !$ticket->pj_id)
                        <form action="{{ route('admin.ticket.assign', $ticket->id) }}" method="POST" class="space-y-2">
                            @csrf
                            <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Pilih PJ / Teknisi</label>
                            <select name="penanggung_jawab" required class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-amber-500">
                                <option value="" disabled selected>-- Pilih PJ --</option>
                                @foreach($activePjs as $pj)
                                    <option value="{{ $pj->id }}">{{ $pj->nama_lengkap }} ({{ $pj->divisi }})</option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full bg-[#0a2540] hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-lg transition text-xs">
                                Tunjuk PJ
                            </button>
                        </form>
                    @elseif($ticket->status === 'Resolved')
                        <form action="{{ route('admin.ticket.close', $ticket->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Ubah status tiket menjadi Closed?')" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2.5 rounded-lg transition text-xs">
                                <i class="fa-solid fa-lock me-1"></i> Ubah Ke Closed
                            </button>
                        </form>
                    @else
                        <p class="text-xs text-slate-400 italic">Tidak ada aksi tersedia untuk status tiket ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>