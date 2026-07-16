<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Sistem Tiketing - Detail Tiket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8 flex items-center justify-center">

    <div class="bg-white rounded-3xl shadow-xl w-full max-w-xl overflow-hidden border border-slate-100">

        <div class="bg-[#0a2540] text-white p-5 sm:p-6 flex items-center gap-2.5">
            <i class="fa-solid fa-circle-info text-amber-400 text-xl"></i>
            <h3 class="text-sm sm:text-lg font-bold tracking-tight">Rincian Laporan Tiket</h3>
        </div>

        <div class="p-5 sm:p-6 space-y-4 text-sm text-slate-700">
            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <div>
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">ID Tiket</p>
                    <p class="font-bold text-slate-800 mt-0.5">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status Progres</p>
                    <p class="mt-0.5">
                        @if($ticket->status == 'Open')
                            <span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase">Open</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase">In Progress</span>
                        @elseif($ticket->status == 'Resolved')
                            <span class="bg-green-100 text-green-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase">Resolved</span>
                        @elseif($ticket->status == 'Closed')
                            <span class="bg-slate-100 text-slate-600 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase">Closed</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Pengajuan</p>
                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <p class="font-medium text-slate-800">{{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</p>
                        @if($ticket->user_edited_at)
                            <p class="text-[11px] text-amber-600 font-semibold mt-0.5">Diubah: {{ $ticket->user_edited_at->format('Y-m-d, H:i') }} WIB</p>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Penanggung Jawab (PJ)</p>
                    <p class="font-semibold text-blue-700 bg-blue-50/50 p-2.5 rounded-xl border border-blue-100/50">{{ $ticket->penanggung_jawab ?? 'Belum Ditentukan' }}</p>
                </div>
            </div>

            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori / Sub-Kategori</p>
                <p class="font-semibold text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor BMN</p>
                    <p class="font-mono text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">{{ $ticket->nomor_bmn ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Prioritas</p>
                    <p class="font-semibold text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">{{ $ticket->prioritas }}</p>
                </div>
            </div>

            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Diselesaikan</p>
                <p class="font-medium text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    {{ $ticket->tanggal_selesai ? $ticket->tanggal_selesai->format('Y-m-d, H:i') . ' WIB' : 'Belum Selesai' }}
                </p>
            </div>

            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Masalah</p>
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 whitespace-pre-line text-slate-600 min-h-[60px] text-xs sm:text-sm">{{ $ticket->deskripsi_masalah }}</div>
            </div>

            @if($ticket->attachment_foto)
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Lampiran Foto Kerusakan</p>
                <div class="border border-slate-100 rounded-xl overflow-hidden bg-slate-50 p-1.5">
                    <img src="{{ asset('storage/' . $ticket->attachment_foto) }}" alt="Lampiran" class="w-full max-h-52 object-contain rounded-lg">
                </div>
            </div>
            @endif

            @if($ticket->hasil_resolved_foto)
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Lampiran Foto Hasil</p>
                <div class="border border-slate-100 rounded-xl overflow-hidden bg-slate-50 p-1.5">
                    <img src="{{ asset('storage/' . $ticket->hasil_resolved_foto) }}" alt="Lampiran" class="w-full max-h-52 object-contain rounded-lg">
                </div>
            </div>
            @endif
        </div>

        <div class="p-4 border-t border-slate-100 flex justify-end bg-slate-50">
            <a href="{{ route('user.dashboard') }}" class="bg-[#0a2540] hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm px-5 py-2 rounded-xl transition shadow-sm">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
</body>
</html>