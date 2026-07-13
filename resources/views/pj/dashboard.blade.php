<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Sistem Tiketing - Dashboard PJ</title>
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

            <div class="flex items-center gap-3 sm:gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Penanggung Jawab</p>
                    <p class="text-sm font-bold text-white mt-0.5">{{ session('nama_lengkap', 'PJ ESDM') }}</p>
                </div>

                <div class="h-6 w-px bg-slate-700 hidden sm:block"></div>

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

        <div class="bg-gradient-to-r from-[#0a2540] to-[#16406c] rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 opacity-5 pointer-events-none bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="relative z-10">
                <span class="bg-amber-400 text-[#0a2540] text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md mb-3 inline-block">Akses Penanggung Jawab</span>
                <h2 class="text-xl sm:text-3xl font-extrabold tracking-tight">Tugas Anda Hari Ini</h2>
                <p class="text-slate-300 text-xs sm:text-sm mt-1">Tinjau, kerjakan, dan selesaikan tiket yang ditugaskan kepada Anda dengan bukti penyelesaian yang jelas.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-hourglass-half"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Menunggu Diterima</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $menunggu }} Tiket</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sedang Dikerjakan</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $diproses }} Tiket</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Selesai</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $selesai }} Tiket</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Tiket yang Ditugaskan Kepada Anda</h3>
                <p class="text-xs text-slate-400">Status pelacakan real-time untuk penugasan Anda</p>
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
                            <th class="p-4 pr-6 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                        @forelse($tickets as $ticket)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 pl-6 font-semibold text-slate-700">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}</p>
                                <span class="text-[11px] text-slate-400">Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</span>
                                @if($ticket->user_edited_at)
                                    <span class="text-[11px] text-amber-600 font-medium block"><i class="fa-solid fa-pen-to-square text-[10px]"></i> Diubah pelapor: {{ $ticket->user_edited_at->format('Y-m-d, H:i') }} WIB</span>
                                @endif
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
                                @endif
                            </td>
                            <td class="p-4 pr-6">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Tombol Detail --}}
                                    <button type="button" class="btn-detail-ticket text-slate-600 hover:text-slate-800 font-semibold text-xs px-2.5 py-1.5 bg-slate-100 rounded-lg transition cursor-pointer"
                                        data-id="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}"
                                        data-kategori="{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}"
                                        data-bmn="{{ $ticket->nomor_bmn ?? '-' }}"
                                        data-prioritas="{{ $ticket->prioritas }}"
                                        data-status="{{ $ticket->status }}"
                                        data-deskripsi="{{ $ticket->deskripsi_masalah }}"
                                        data-tanggal="{{ $ticket->created_at->format('Y-m-d, H:i') }} WIB"
                                        data-pelapor="{{ $ticket->pelapor->nama_lengkap ?? '-' }}"
                                        data-divisi="{{ $ticket->pelapor->divisi ?? '-' }}"
                                        data-selesai="{{ $ticket->tanggal_selesai ? $ticket->tanggal_selesai->format('Y-m-d, H:i') . ' WIB' : 'Belum Selesai' }}"
                                        data-foto="{{ $ticket->attachment_foto ? asset('storage/' . $ticket->attachment_foto) : '' }}"
                                        data-hasil="{{ $ticket->hasil_resolved_foto ? asset('storage/' . $ticket->hasil_resolved_foto) : '' }}">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </button>

                                    {{-- Tombol Aksi --}}
                                    @if($ticket->status == 'Open')
                                        <form action="{{ route('pj.ticket.terima', $ticket->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-amber-400 hover:bg-amber-300 text-[#0a2540] font-bold text-xs px-3 py-2 rounded-lg transition">
                                                Mulai Kerjakan
                                            </button>
                                        </form>
                                    @elseif($ticket->status == 'In Progress')
                                        <button type="button" class="btn-selesaikan bg-green-500 hover:bg-green-600 text-white font-bold text-xs px-3 py-2 rounded-lg transition"
                                            data-id="{{ $ticket->id }}" data-code="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}">
                                            Selesaikan
                                        </button>
                                    @else
                                        <span class="text-slate-300 text-xs italic">Sudah Selesai</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="p-8 text-center text-slate-400 text-sm">Belum ada tiket yang ditugaskan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ===== VERSI MOBILE ===== --}}
            <div class="md:hidden p-4 space-y-4">
                @forelse($tickets as $ticket)
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-slate-700">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                        @if($ticket->status == 'Open')
                            <span class="bg-amber-100 text-amber-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Open</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="bg-blue-100 text-blue-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Diproses</span>
                        @else
                            <span class="bg-green-100 text-green-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Selesai</span>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">{{ $ticket->kategori }}</h4>
                        <p class="text-xs text-slate-600 mt-0.5">{{ $ticket->sub_kategori }}</p>
                        <p class="text-[11px] text-slate-400 mt-2"><i class="fa-solid fa-user"></i> Pelapor: {{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                        <p class="text-[10px] text-slate-400"><i class="fa-regular fa-clock"></i> Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</p>
                    </div>
                    <div class="grid grid-cols-2 gap-1.5 pt-2 border-t border-slate-200/60">
                        {{-- Tombol Detail --}}
                        <button type="button" class="btn-detail-ticket text-center text-slate-600 font-bold text-xs py-2 bg-white border border-slate-200 rounded-xl cursor-pointer"
                            data-id="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}"
                            data-kategori="{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}"
                            data-bmn="{{ $ticket->nomor_bmn ?? '-' }}"
                            data-prioritas="{{ $ticket->prioritas }}"
                            data-status="{{ $ticket->status }}"
                            data-deskripsi="{{ $ticket->deskripsi_masalah }}"
                            data-tanggal="{{ $ticket->created_at->format('Y-m-d, H:i') }} WIB"
                            data-pelapor="{{ $ticket->pelapor->nama_lengkap ?? '-' }}"
                            data-divisi="{{ $ticket->pelapor->divisi ?? '-' }}"
                            data-selesai="{{ $ticket->tanggal_selesai ? $ticket->tanggal_selesai->format('Y-m-d, H:i') . ' WIB' : 'Belum Selesai' }}"
                            data-foto="{{ $ticket->attachment_foto ? asset('storage/' . $ticket->attachment_foto) : '' }}"
                            data-hasil="{{ $ticket->hasil_resolved_foto ? asset('storage/' . $ticket->hasil_resolved_foto) : '' }}">
                            Detail
                        </button>

                        {{-- Tombol Aksi --}}
                        @if($ticket->status == 'Open')
                            <form action="{{ route('pj.ticket.terima', $ticket->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-amber-400 hover:bg-amber-300 text-[#0a2540] font-bold text-xs py-2 rounded-xl transition">
                                    Mulai Kerjakan
                                </button>
                            </form>
                        @elseif($ticket->status == 'In Progress')
                            <button type="button" class="btn-selesaikan bg-green-500 hover:bg-green-600 text-white font-bold text-xs py-2 rounded-xl transition"
                                data-id="{{ $ticket->id }}" data-code="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}">
                                Selesaikan
                            </button>
                        @else
                            <span class="text-slate-300 text-xs italic text-center py-2">Selesai</span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-center text-slate-400 text-sm py-4">Belum ada tiket yang ditugaskan.</p>
                @endforelse
            </div>

            <div class="p-4 border-t border-slate-100">{{ $tickets->links() }}</div>
        </div>
    </main>

    {{-- ==================== MODAL DETAIL TIKET ==================== --}}
    <div id="detailModal" class="fixed inset-0 bg-slate-900/60 items-center justify-center p-4 z-50 hidden transition-all opacity-0 duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden transform scale-95 transition-all duration-300 max-h-[90vh] flex flex-col">
            <div class="bg-[#0a2540] text-white p-5 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-info text-amber-400 text-xl"></i>
                    <h3 class="text-base sm:text-lg font-bold tracking-tight">Rincian Laporan Tiket</h3>
                </div>
            </div>

            <div class="p-6 space-y-4 text-sm text-slate-700 overflow-y-auto flex-1">
                <div class="grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">ID Tiket</p>
                        <p id="detailId" class="font-bold text-slate-800 mt-0.5">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status Progres</p>
                        <p id="detailStatus" class="mt-0.5">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pelapor</p>
                        <p id="detailPelapor" class="font-semibold text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Divisi Pelapor</p>
                        <p id="detailDivisi" class="font-semibold text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">-</p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori / Sub-Kategori</p>
                    <p id="detailKategori" class="font-semibold text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">-</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor BMN</p>
                        <p id="detailBmn" class="font-mono text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Prioritas</p>
                        <p id="detailPrioritas" class="font-semibold text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">-</p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Masalah</p>
                    <div id="detailDeskripsi" class="bg-slate-50 p-3 rounded-xl border border-slate-100 whitespace-pre-line text-slate-600 min-h-[60px] text-xs sm:text-sm"></div>
                </div>

                <div id="detailFotoContainer" class="hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Foto Kerusakan (dari Pelapor)</p>
                    <div class="border border-slate-100 rounded-xl overflow-hidden bg-slate-50 p-1.5">
                        <img id="detailFoto" src="" alt="Foto Kerusakan" class="w-full max-h-52 object-contain rounded-lg">
                    </div>
                </div>

                <div id="detailHasilContainer" class="hidden">
                    <p class="text-[10px] font-bold text-green-600 uppercase tracking-wider mb-1">Foto Bukti Penyelesaian (Anda)</p>
                    <div class="border border-green-100 rounded-xl overflow-hidden bg-green-50/30 p-1.5">
                        <img id="detailHasil" src="" alt="Bukti Penyelesaian" class="w-full max-h-52 object-contain rounded-lg">
                    </div>
                    <p id="detailSelesai" class="text-[11px] text-slate-400 mt-1">-</p>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 flex justify-end bg-slate-50 shrink-0">
                <button type="button" id="btnCloseDetailContainer" class="bg-[#0a2540] hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm px-5 py-2 rounded-xl transition shadow-sm cursor-pointer">Tutup Rincian</button>
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
                <button type="button" onclick="closeSelesaikanModal()" class="text-slate-300 hover:text-white transition"><i class="fa-solid fa-xmark"></i></button>
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

        const detailModal = document.getElementById('detailModal');
        document.getElementById('btnCloseDetailContainer').addEventListener('click', () => {
            detailModal.classList.add('opacity-0');
            detailModal.querySelector('.max-w-xl').classList.add('scale-95');
            setTimeout(() => {
                detailModal.classList.add('hidden');
                detailModal.classList.remove('flex');
            }, 300);
        });

        document.querySelectorAll('.btn-detail-ticket').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('detailId').innerText = this.getAttribute('data-id');
                document.getElementById('detailKategori').innerText = this.getAttribute('data-kategori');
                document.getElementById('detailBmn').innerText = this.getAttribute('data-bmn');
                document.getElementById('detailPrioritas').innerText = this.getAttribute('data-prioritas');
                document.getElementById('detailDeskripsi').innerText = this.getAttribute('data-deskripsi');
                document.getElementById('detailPelapor').innerText = this.getAttribute('data-pelapor');
                document.getElementById('detailDivisi').innerText = this.getAttribute('data-divisi');
                document.getElementById('detailSelesai').innerText = this.getAttribute('data-selesai');

                const status = this.getAttribute('data-status');
                const statusEl = document.getElementById('detailStatus');
                if (status === 'Open') {
                    statusEl.innerHTML = `<span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase">Open</span>`;
                } else if (status === 'In Progress') {
                    statusEl.innerHTML = `<span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase">Diproses</span>`;
                } else {
                    statusEl.innerHTML = `<span class="bg-green-100 text-green-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase">Selesai</span>`;
                }

                const foto = this.getAttribute('data-foto');
                const fotoContainer = document.getElementById('detailFotoContainer');
                const fotoImg = document.getElementById('detailFoto');
                if (foto) { fotoImg.src = foto; fotoContainer.classList.remove('hidden'); }
                else { fotoImg.src = ''; fotoContainer.classList.add('hidden'); }

                const hasil = this.getAttribute('data-hasil');
                const hasilContainer = document.getElementById('detailHasilContainer');
                const hasilImg = document.getElementById('detailHasil');
                if (hasil) { hasilImg.src = hasil; hasilContainer.classList.remove('hidden'); }
                else { hasilImg.src = ''; hasilContainer.classList.add('hidden'); }

                detailModal.classList.remove('hidden');
                detailModal.classList.add('flex');
                setTimeout(() => {
                    detailModal.classList.remove('opacity-0');
                    detailModal.querySelector('.max-w-xl').classList.remove('scale-95');
                }, 50);
            });
        });

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
    </script>
</body>
</html>