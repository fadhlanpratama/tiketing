<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Sistem Tiketing - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased flex flex-col">

    <header class="bg-[#0a2540] text-white sticky top-0 z-30 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('image/esdm.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                <div>
                    <h1 class="text-white font-black tracking-wider text-base sm:text-lg leading-tight">SISTEM TIKETING</h1>
                    <span class="text-[9px] sm:text-[10px] text-amber-400 uppercase font-bold tracking-widest block">Portal Pengguna</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="text-right hidden sm:flex items-center gap-3">
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Pengguna</p>
                        <p class="text-sm font-bold text-white mt-0.5">{{ session('nama_lengkap', 'Pegawai ESDM') }}</p>
                    </div>
                    <a href="#" class="bg-slate-700/50 hover:bg-slate-700 text-slate-300 hover:text-white w-9 h-9 flex items-center justify-center rounded-xl transition text-xs" title="Edit Profil">
                        <i class="fa-solid fa-user-gear text-sm"></i>
                    </a>
                </div>
                
                <a href="#" class="sm:hidden bg-slate-700/40 text-amber-400 w-10 h-10 flex flex-col items-center justify-center rounded-xl border border-slate-700/60 active:scale-95 transition" title="Edit Profil">
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

        <div class="bg-gradient-to-r from-[#0a2540] to-[#16406c] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-5 pointer-events-none bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="relative z-10">
                <span class="bg-amber-400 text-[#0a2540] text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md mb-3 inline-block">Akses Pengguna</span>
                <h2 class="text-xl sm:text-3xl font-extrabold tracking-tight">Butuh Bantuan?</h2>
                <p class="text-slate-300 text-xs sm:text-sm mt-1">Buat, pantau, dan kelola semua permintaan tiket internal Anda dengan mudah.</p>
            </div>
            
            <a href="{{ route('user.ticket.create') }}" class="relative z-10 bg-amber-400 hover:bg-amber-300 text-[#0a2540] font-bold text-sm px-6 py-3 rounded-xl shadow-lg transition transform active:scale-95 flex items-center gap-2 justify-center w-full sm:w-auto shrink-0">
                <i class="fa-solid fa-plus"></i> Buat Tiket Baru
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-folder-open"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tiket Aktif</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $TiketAktif }} Tiket</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl shrink-0"><i class="fa-solid fa-spinner"></i></div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dalam Proses</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $dalamProses }} Tiket</h3>
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
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Pengajuan Tiket</h3>
                    <p class="text-xs text-slate-400">Status pelacakan real-time untuk permohonan layanan internal Anda</p>
                </div>
            </div>

            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-semibold text-xs tracking-wider border-b border-slate-100">
                            <th class="p-4 pl-6">ID TIKET</th>
                            <th class="p-4">KATEGORI PERMINTAAN</th>
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
                                <div class="flex flex-col sm:flex-row sm:items-center gap-x-2 text-[11px] text-slate-400 mt-0.5">
                                    <span>Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</span>
                                    @if($ticket->updated_at && $ticket->updated_at->ne($ticket->created_at))
                                        <span class="hidden sm:inline text-slate-300">|</span>
                                        <span class="text-amber-600 font-medium"><i class="fa-solid fa-pen-to-square text-[10px]"></i> Diubah: {{ $ticket->updated_at->format('Y-m-d, H:i') }} WIB</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                @if(strtolower($ticket->prioritas) == 'high' || strtolower($ticket->prioritas) == 'tinggi')
                                    <span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded-lg text-xs border border-red-100"><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $ticket->prioritas }}</span>
                                @elseif(strtolower($ticket->prioritas) == 'medium' || strtolower($ticket->prioritas) == 'sedang')
                                    <span class="text-amber-600 font-bold bg-amber-50 px-2 py-1 rounded-lg text-xs border border-amber-100"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $ticket->prioritas }}</span>
                                @else
                                    <span class="text-slate-600 font-bold bg-slate-100 px-2 py-1 rounded-lg text-xs"><i class="fa-solid fa-circle-info mr-1"></i>{{ $ticket->prioritas ?? 'Low' }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($ticket->status == 'Open')
                                    <span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Open</span>
                                @elseif($ticket->status == 'In Progress')
                                    <span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Diproses</span>
                                @else
                                    <span class="bg-green-100 text-green-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Selesai</span>
                                @endif
                            </td>
                            <td class="p-4 pr-6 flex items-center justify-center gap-2">
                                <button type="button" class="btn-detail-ticket text-slate-600 hover:text-slate-800 font-semibold text-xs px-2.5 py-1.5 bg-slate-100 rounded-lg transition cursor-pointer" 
                                    data-id="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}" 
                                    data-kategori="{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}" 
                                    data-bmn="{{ $ticket->nomor_bmn ?? '-' }}" 
                                    data-prioritas="{{ $ticket->prioritas }}" 
                                    data-status="{{ $ticket->status }}" 
                                    data-deskripsi="{{ $ticket->deskripsi_masalah }}" 
                                    data-tanggal="{{ $ticket->created_at->format('Y-m-d, H:i') }} WIB" 
                                    data-diubah="@if($ticket->updated_at && $ticket->updated_at->ne($ticket->created_at)) Diubah: {{ $ticket->updated_at->format('Y-m-d, H:i') }} WIB @endif"
                                    data-pj="{{ $ticket->penanggung_jawab ?? 'Belum Ditentukan' }}" 
                                    data-selesai="{{ $ticket->tanggal_selesai ? $ticket->tanggal_selesai->format('Y-m-d, H:i') . ' WIB' : 'Belum Selesai' }}" 
                                    data-foto="{{ $ticket->attachment_foto ? asset('storage/' . $ticket->attachment_foto) : '' }}">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </button>
                                <a href="{{ route('user.ticket.edit', $ticket->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs px-2.5 py-1.5 bg-blue-50 rounded-lg transition"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                <button type="button" class="btn-delete-trigger text-red-600 hover:text-red-800 font-semibold text-xs px-2.5 py-1.5 bg-red-50 rounded-lg transition cursor-pointer" data-id="{{ $ticket->id }}" data-code="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}"><i class="fa-solid fa-trash-can"></i> Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 text-sm">Belum ada riwayat pengajuan tiket.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="md:hidden p-4 space-y-4">
                @forelse($tickets as $ticket)
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-3 relative">
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
                        
                        <div class="space-y-0.5 mt-2">
                            <span class="text-[10px] text-slate-400 block"><i class="fa-regular fa-clock"></i> Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</span>
                            @if($ticket->updated_at && $ticket->updated_at->ne($ticket->created_at))
                                <span class="text-[10px] text-amber-600 font-medium block"><i class="fa-solid fa-pen-to-square"></i> Diubah: {{ $ticket->updated_at->format('Y-m-d, H:i') }} WIB</span>
                            @endif
                        </div>

                        <span class="text-[10px] font-semibold text-slate-500 block mt-2">
                            <i class="fa-solid fa-layer-group"></i> Prioritas: 
                            <span class="font-bold {{ (strtolower($ticket->prioritas) == 'high' || strtolower($ticket->prioritas) == 'tinggi') ? 'text-red-600' : ((strtolower($ticket->prioritas) == 'medium' || strtolower($ticket->prioritas) == 'sedang') ? 'text-amber-600' : 'text-slate-600') }}">
                                {{ $ticket->prioritas ?? 'Low' }}
                            </span>
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-1.5 pt-2 border-t border-slate-200/60">
                        <button type="button" class="btn-detail-ticket text-center text-slate-600 font-bold text-xs py-2 bg-white border border-slate-200 rounded-xl cursor-pointer" 
                            data-id="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}" 
                            data-kategori="{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}" 
                            data-bmn="{{ $ticket->nomor_bmn ?? '-' }}" 
                            data-prioritas="{{ $ticket->prioritas }}" 
                            data-status="{{ $ticket->status }}" 
                            data-deskripsi="{{ $ticket->deskripsi_masalah }}" 
                            data-tanggal="{{ $ticket->created_at->format('Y-m-d, H:i') }} WIB" 
                            data-diubah="@if($ticket->updated_at && $ticket->updated_at->ne($ticket->created_at)) Diubah: {{ $ticket->updated_at->format('Y-m-d, H:i') }} WIB @endif"
                            data-pj="{{ $ticket->penanggung_jawab ?? 'Belum Ditentukan' }}" 
                            data-selesai="{{ $ticket->tanggal_selesai ? $ticket->tanggal_selesai->format('Y-m-d, H:i') . ' WIB' : 'Belum Selesai' }}" 
                            data-foto="{{ $ticket->attachment_foto ? asset('storage/' . $ticket->attachment_foto) : '' }}">Detail</button>
                        <a href="{{ route('user.ticket.edit', $ticket->id) }}" class="text-center text-blue-600 font-bold text-xs py-2 bg-white border border-slate-200 rounded-xl">Edit</a>
                        <button type="button" class="btn-delete-trigger text-center text-red-600 font-bold text-xs py-2 bg-white border border-slate-200 rounded-xl cursor-pointer" data-id="{{ $ticket->id }}" data-code="#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}">Hapus</button>
                    </div>
                </div>
                @empty
                <p class="text-center text-slate-400 text-sm py-4">Belum ada riwayat pengajuan tiket.</p>
                @endforelse
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $tickets->links() }}
            </div>
        </div>
    </main>

    <div id="detailModal" class="fixed inset-0 bg-slate-900/60 items-center justify-center p-4 z-50 hidden transition-all opacity-0 duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden transform scale-95 transition-all duration-300 max-h-[90vh] flex flex-col">
            <div class="bg-[#0a2540] text-white p-5 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-info text-amber-400 text-xl"></i>
                    <h3 class="text-base sm:text-lg font-bold tracking-tight">Rincian Laporan Tiket</h3>
                </div>
                <button id="btnCloseDetailModal" style="display:none;"><i class="fa-solid fa-xmark"></i></button>
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
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Pengajuan</p>
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <p id="detailTanggal" class="font-medium text-slate-800">-</p>
                            <p id="detailDiubah" class="text-[11px] text-amber-600 font-semibold mt-0.5"></p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Penanggung Jawab (PJ)</p>
                        <p id="detailPj" class="font-semibold text-blue-700 bg-blue-50/50 p-2.5 rounded-xl border border-blue-100/50">-</p>
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
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Diselesaikan</p>
                    <p id="detailSelesai" class="font-medium text-slate-800 bg-slate-50 p-2.5 rounded-xl border border-slate-100">-</p>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Masalah</p>
                    <div id="detailDeskripsi" class="bg-slate-50 p-3 rounded-xl border border-slate-100 whitespace-pre-line text-slate-600 min-h-[60px] text-xs sm:text-sm"></div>
                </div>

                <div id="detailFotoContainer" class="hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Lampiran Foto Kerusakan</p>
                    <div class="border border-slate-100 rounded-xl overflow-hidden bg-slate-50 p-1.5">
                        <img id="detailFoto" src="" alt="Lampiran" class="w-full max-h-52 object-contain rounded-lg">
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-slate-100 flex justify-end bg-slate-50 shrink-0">
                <button type="button" id="btnCloseDetailContainer" class="bg-[#0a2540] hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm px-5 py-2 rounded-xl transition shadow-sm cursor-pointer">Tutup Rincian</button>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 bg-slate-900/60 items-center justify-center p-4 z-50 hidden transition-all opacity-0 duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-all duration-300 p-6 text-center space-y-4">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-3xl mx-auto animate-bounce">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Hapus Laporan Tiket?</h3>
                <p class="text-xs text-slate-400 mt-1">Apakah Anda benar-benar yakin ingin menghapus tiket <span id="deleteTicketCode" class="font-bold text-slate-700"></span>? Tindakan ini tidak dapat dikembalikan.</p>
            </div>
            
            <form id="deleteTicketForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <button type="button" onclick="closeDeleteModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm py-2.5 rounded-xl transition cursor-pointer">Batal</button>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold text-sm py-2.5 rounded-xl transition shadow-md cursor-pointer">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div id="toastSuccess" class="fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-[#0a2540] text-white px-5 py-3.5 rounded-2xl shadow-2xl transition-all duration-500 transform translate-y-10 opacity-0 max-w-sm border border-slate-700/50">
        <div class="w-7 h-7 bg-green-500 text-white rounded-full flex items-center justify-center text-sm shrink-0"><i class="fa-solid fa-check"></i></div>
        <p class="text-xs font-semibold tracking-wide pr-2">{{ session('success') }}</p>
        <button type="button" onclick="closeToast()" class="text-slate-400 hover:text-white transition ml-auto cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    <script>
        const toast = document.getElementById('toastSuccess');
        if (toast) {
            setTimeout(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 100);
            setTimeout(() => { closeToast(); }, 5000);
        }
        function closeToast() {
            if (toast) {
                toast.classList.add('opacity-0', 'translate-y-10');
                setTimeout(() => { toast.remove(); }, 500);
            }
        }

        const detailModal = document.getElementById('detailModal');
        const btnCloseDetailModal = document.getElementById('btnCloseDetailModal');
        const btnCloseDetailContainer = document.getElementById('btnCloseDetailContainer');

        const closeDetailModal = () => {
            detailModal.classList.add('opacity-0');
            detailModal.querySelector('.max-w-xl').classList.add('scale-95');
            setTimeout(() => {
                detailModal.classList.add('hidden');
                detailModal.classList.remove('flex');
            }, 300);
        };

        btnCloseDetailModal.addEventListener('click', closeDetailModal);
        btnCloseDetailContainer.addEventListener('click', closeDetailModal);

        document.querySelectorAll('.btn-detail-ticket').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const kategori = this.getAttribute('data-kategori');
                const bmn = this.getAttribute('data-bmn');
                const prioritas = this.getAttribute('data-prioritas');
                const status = this.getAttribute('data-status');
                const deskripsi = this.getAttribute('data-deskripsi');
                const foto = this.getAttribute('data-foto');
                const tanggal = this.getAttribute('data-tanggal');
                const diubah = this.getAttribute('data-diubah');
                const pj = this.getAttribute('data-pj');
                const selesai = this.getAttribute('data-selesai');

                document.getElementById('detailId').innerText = id;
                document.getElementById('detailKategori').innerText = kategori;
                document.getElementById('detailBmn').innerText = bmn;
                document.getElementById('detailPrioritas').innerText = prioritas;
                document.getElementById('detailDeskripsi').innerText = deskripsi;
                document.getElementById('detailTanggal').innerText = tanggal;
                document.getElementById('detailPj').innerText = pj;
                document.getElementById('detailSelesai').innerText = selesai;

                const diubahEl = document.getElementById('detailDiubah');
                if(diubah && diubah.trim() !== "") {
                    diubahEl.innerText = diubah;
                    diubahEl.style.display = 'block';
                } else {
                    diubahEl.innerText = '';
                    diubahEl.style.display = 'none';
                }

                const statusEl = document.getElementById('detailStatus');
                if (status === 'Open') {
                    statusEl.innerHTML = `<span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Open</span>`;
                } else if (status === 'In Progress') {
                    statusEl.innerHTML = `<span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Diproses</span>`;
                } else {
                    statusEl.innerHTML = `<span class="bg-green-100 text-green-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Selesai</span>`;
                }

                const fotoContainer = document.getElementById('detailFotoContainer');
                const fotoImg = document.getElementById('detailFoto');
                if (foto) {
                    fotoImg.src = foto;
                    fotoContainer.classList.remove('hidden');
                } else {
                    fotoImg.src = '';
                    fotoContainer.classList.add('hidden');
                }

                detailModal.classList.remove('hidden');
                detailModal.classList.add('flex');
                setTimeout(() => {
                    detailModal.classList.remove('opacity-0');
                    detailModal.querySelector('.max-w-xl').classList.remove('scale-95');
                }, 50);
            });
        });

        const deleteModal = document.getElementById('deleteModal');
        
        document.querySelectorAll('.btn-delete-trigger').forEach(button => {
            button.addEventListener('click', function() {
                const ticketId = this.getAttribute('data-id');
                const ticketCode = this.getAttribute('data-code');

                document.getElementById('deleteTicketCode').innerText = ticketCode;
                document.getElementById('deleteTicketForm').setAttribute('action', `/user/ticket/${ticketId}`);
                
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
                setTimeout(() => {
                    deleteModal.classList.remove('opacity-0');
                    deleteModal.querySelector('.max-w-sm').classList.remove('scale-95');
                }, 50);
            });
        });

        function closeDeleteModal() {
            deleteModal.classList.add('opacity-0');
            deleteModal.querySelector('.max-w-sm').classList.add('scale-95');
            setTimeout(() => {
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('flex');
            }, 300);
        }
    </script>
</body>
</html>