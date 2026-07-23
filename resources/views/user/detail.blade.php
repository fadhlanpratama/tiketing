<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Sistem Tiketing - Detail Tiket </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body class="bg-slate-100/80 min-h-screen font-sans text-slate-800 antialiased p-3 sm:p-6 lg:p-8">

    <div class="max-w-6xl mx-auto space-y-5">

        {{-- Header Navigation Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:px-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div class="flex items-center gap-3">
                <a href="{{ route('user.dashboard') }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Kembali">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                            #TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                        </h1>
                        {{-- Status Badge --}}
                        @if($ticket->status == 'Open')
                            <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Open</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">In Progress</span>
                        @elseif($ticket->status == 'Resolved')
                            <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Resolved</span>
                        @elseif($ticket->status == 'Closed')
                            <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Closed</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400">Diajukan pada {{ $ticket->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('user.dashboard') }}" class="w-full sm:w-auto bg-[#0a2540] hover:bg-slate-800 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-house text-xs"></i>
                    <span>Ke Dashboard</span>
                </a>
            </div>
        </div>

        {{-- Main Layout: 2 Columns Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            
            {{-- KOLOM KIRI (2/3): Informational Content --}}
            <div class="lg:col-span-2 space-y-5">
                
                {{-- Card Deskripsi Utama --}}
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-200/80 space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-2.5 py-1 rounded-md">
                            {{ $ticket->kategori }}
                        </span>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900 mt-2">
                            {{ $ticket->sub_kategori }}
                        </h2>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Deskripsi Masalah / Laporan</h3>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line min-h-[90px]">
                            {{ $ticket->deskripsi_masalah }}
                        </div>
                    </div>

                    {{-- Attribute Meta Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nomor BMN</p>
                            <p class="text-xs font-mono font-bold text-slate-800 mt-0.5">{{ $ticket->nomor_bmn ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Prioritas Tiket</p>
                            <p class="text-xs font-bold mt-0.5 {{ (strtolower($ticket->prioritas) == 'high' || strtolower($ticket->prioritas) == 'tinggi') ? 'text-red-600' : ((strtolower($ticket->prioritas) == 'medium' || strtolower($ticket->prioritas) == 'sedang') ? 'text-amber-600' : 'text-slate-700') }}">
                                {{ $ticket->prioritas ?? 'Low' }}
                            </p>
                        </div>
                        <div class="col-span-2 sm:col-span-1 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Selesai</p>
                            <p class="text-xs font-semibold text-slate-800 mt-0.5">
                                {{ $ticket->tanggal_selesai ? $ticket->tanggal_selesai->format('d/m/Y H:i') : 'Dalam Proses' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card Lampiran Foto --}}
                @if($ticket->attachment_foto || $ticket->hasil_resolved_foto)
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-200/80 space-y-4">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-paperclip text-blue-600"></i> Lampiran Dokumentasi
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($ticket->attachment_foto)
                        <div>
                            <p class="text-[11px] font-bold text-slate-500 mb-1.5"><i class="fa-regular fa-image mr-1"></i> Foto Kerusakan Awal</p>
                            <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50 group relative">
                                <img src="{{ asset('storage/' . $ticket->attachment_foto) }}" alt="Foto Kerusakan" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                                <a href="{{ asset('storage/' . $ticket->attachment_foto) }}" target="_blank" class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-semibold transition">
                                    <i class="fa-solid fa-expand mr-1.5"></i> Perbesar
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($ticket->hasil_resolved_foto)
                        <div>
                            <p class="text-[11px] font-bold text-green-600 mb-1.5"><i class="fa-solid fa-circle-check mr-1"></i> Foto Hasil Perbaikan</p>
                            <div class="border border-green-200 rounded-xl overflow-hidden bg-green-50/50 group relative">
                                <img src="{{ asset('storage/' . $ticket->hasil_resolved_foto) }}" alt="Foto Hasil" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                                <a href="{{ asset('storage/' . $ticket->hasil_resolved_foto) }}" target="_blank" class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-semibold transition">
                                    <i class="fa-solid fa-expand mr-1.5"></i> Perbesar
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>

            {{-- KOLOM KANAN (1/3): Sidebar Meta & Diskusi --}}
            <div class="space-y-5">

                {{-- Status PJ & Informasi Petugas --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 space-y-3">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Penanggung Jawab (PJ)</h3>
                    <div class="flex items-center gap-3 p-3 bg-blue-50/60 rounded-xl border border-blue-100">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-blue-950 truncate">{{ $ticket->penanggung_jawab ?? 'Belum Ditentukan' }}</p>
                            <p class="text-[10px] text-blue-600 font-medium">Tim Teknis ESDM</p>
                        </div>
                    </div>

                    @if($ticket->status === 'Closed' && $ticket->closed_by === 'user')
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-center">
                        <p class="text-xs text-slate-500 font-medium italic"><i class="fa-solid fa-user-slash mr-1"></i> Tiket telah ditutup oleh Anda</p>
                    </div>
                    @endif
                </div>

                {{-- Include Partial Komentar & Pop-up Survei --}}
                @include('partials.ticket-chat-survey', ['chatRoute' => 'user.ticket.chat', 'isPj' => false])

            </div>
        </div>

    </div>

</body>
</html>