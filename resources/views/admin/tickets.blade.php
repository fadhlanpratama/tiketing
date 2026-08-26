@extends('admin.layout')

@section('title', 'Manajemen Tiket')
@section('page-title', 'Manajemen Tiket Helpdesk')
@section('page-desc', 'Daftar penugasan PJ dan verifikasi tiket yang siap ditutup')

@section('content')
<div class="space-y-8">

    <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-4 flex items-center gap-3 text-amber-900 shadow-sm">
        <div class="w-9 h-9 rounded-xl bg-amber-400 text-[#0a2540] flex items-center justify-center shrink-0 font-bold">
            <i class="fa-solid fa-hand-pointer"></i>
        </div>
        <div class="text-xs">
            <p class="font-bold">Petunjuk Penunjukan PJ / Verifikasi Tiket:</p>
            <p class="text-amber-800/90 mt-0.5">Silakan *klik baris/row tiket* pada tabel di bawah ini untuk masuk ke halaman detail tiket lalu menunjuk Teknisi/PJ atau mengubah status ke Closed.</p>
        </div>
    </div>

    <!-- ===== SECTION 1: PENUGASAN PJ (TIKET OPEN) ===== -->
    <section class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-[#0a2540] flex items-center justify-center font-bold">
                    <i class="fa-solid fa-user-gear text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm sm:text-base">Penugasan PJ (Tiket Open)</h3>
                    <p class="text-[11px] text-slate-400">Klik baris tiket untuk memilih dan menunjuk PJ Teknisi.</p>
                </div>
            </div>
            <span class="bg-amber-100 text-[#0a2540] text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                {{ $unassignedTickets->count() }} Belum Ditunjuk PJ
            </span>
        </div>

        <!-- Tabel Desktop Section 1 -->
        <div class="hidden md:block overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                        <th class="p-4 pl-6">ID TIKET</th>
                        <th class="p-4">KATEGORI</th>
                        <th class="p-4">PELAPOR</th>
                        <th class="p-4">PRIORITAS</th>
                        <th class="p-4 pr-6">STATUS</th>
                    </tr>
                </thead>
                <tbody id="tbodyOpen" class="text-xs text-slate-600 divide-y divide-slate-100">
                    @forelse($unassignedTickets as $ticket)
                    <tr class="desktop-row-open clickable-row cursor-pointer hover:bg-slate-50/80 transition" data-url="{{ route('admin.ticket.show', $ticket->id) }}">
                        <td class="p-4 pl-6 font-bold text-slate-800">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}</p>
                            <span class="text-[11px] text-slate-400">Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</span>
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $ticket->pelapor->nama_lengkap ?? 'User' }}</p>
                            <span class="text-[10px] text-slate-400">{{ $ticket->pelapor->divisi ?? '-' }}</span>
                        </td>
                        <td class="p-4">
                            @if(strtolower($ticket->prioritas) == 'tinggi')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border border-rose-200 bg-rose-50 text-rose-600">Tinggi</span>
                            @elseif(strtolower($ticket->prioritas) == 'sedang')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border border-amber-200 bg-amber-50 text-amber-700">Sedang</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border border-slate-200 bg-slate-50 text-slate-600">Rendah</span>
                            @endif
                        </td>
                        <td class="p-4 pr-6">
                            <span class="bg-amber-100 text-[#0a2540] text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Open</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-8 text-center text-slate-400">Tidak ada tiket yang butuh penunjukan PJ.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tampilan Mobile Section 1 -->
        <div id="containerOpenMobile" class="md:hidden p-4 space-y-3">
            @forelse($unassignedTickets as $ticket)
            <div class="mobile-row-open clickable-row bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2 cursor-pointer hover:bg-slate-100/60 transition" data-url="{{ route('admin.ticket.show', $ticket->id) }}">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-800 text-xs">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="bg-amber-100 text-[#0a2540] text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">Open</span>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-xs">{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}</h4>
                    <span class="text-[10px] text-slate-400">Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</span>
                </div>
                <div class="text-[11px] text-slate-500 space-y-0.5 pt-1">
                    <p><i class="fa-solid fa-user me-1 text-slate-400"></i>Pelapor: {{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                    <p><i class="fa-solid fa-layer-group me-1 text-slate-400"></i>Prioritas: <span class="font-bold text-slate-700">{{ $ticket->prioritas }}</span></p>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-400 text-xs py-4">Tidak ada tiket yang butuh penunjukan PJ.</p>
            @endforelse
        </div>

        <!-- Pager Controls Section 1 (10 Items) -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 font-semibold">
            <span id="infoOpen">Menampilkan 0 - 0 dari 0 data</span>
            <div class="flex items-center gap-1.5" id="pagerOpen"></div>
        </div>
    </section>

    <!-- ===== SECTION 2: TIKET RESOLVED / SIAP CLOSED ===== -->
    <section class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm sm:text-base">Tiket Resolved (Siap Diclosed Admin)</h3>
                    <p class="text-[11px] text-slate-400">Klik baris tiket untuk memverifikasi bukti foto penanganan dan menutup tiket.</p>
                </div>
            </div>
            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                {{ $resolvedTickets->count() }} Siap Verifikasi
            </span>
        </div>

        <!-- Tabel Desktop Section 2 -->
        <div class="hidden md:block overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                        <th class="p-4 pl-6">ID TIKET</th>
                        <th class="p-4">KATEGORI</th>
                        <th class="p-4">PELAPOR</th>
                        <th class="p-4">PENANGGUNG JAWAB</th>
                        <th class="p-4">PRIORITAS</th>
                        <th class="p-4 pr-6">STATUS</th>
                    </tr>
                </thead>
                <tbody id="tbodyResolved" class="text-xs text-slate-600 divide-y divide-slate-100">
                    @forelse($resolvedTickets as $ticket)
                    <tr class="desktop-row-resolved clickable-row cursor-pointer hover:bg-slate-50/80 transition" data-url="{{ route('admin.ticket.show', $ticket->id) }}">
                        <td class="p-4 pl-6 font-bold text-slate-800">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}</p>
                            <span class="text-[11px] text-slate-400">Selesai: {{ $ticket->tanggal_selesai ? \Carbon\Carbon::parse($ticket->tanggal_selesai)->format('Y-m-d, H:i') : $ticket->updated_at->format('Y-m-d, H:i') }} WIB</span>
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">{{ $ticket->pelapor->nama_lengkap ?? 'User' }}</p>
                            <span class="text-[10px] text-slate-400">{{ $ticket->pelapor->divisi ?? '-' }}</span>
                        </td>
                        <td class="p-4">
                            <p class="font-bold text-slate-800">
                                {{ optional($ticket->pj)->nama_lengkap ?? $ticket->penanggung_jawab ?? '-' }}
                            </p>
                            <span class="text-[10px] text-slate-400">
                                {{ optional($ticket->pj)->divisi ?? '-' }}
                            </span>
                        </td>
                        <td class="p-4">
                            @if(strtolower($ticket->prioritas) == 'tinggi')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border border-rose-200 bg-rose-50 text-rose-600">Tinggi</span>
                            @elseif(strtolower($ticket->prioritas) == 'sedang')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border border-amber-200 bg-amber-50 text-amber-700">Sedang</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border border-slate-200 bg-slate-50 text-slate-600">Rendah</span>
                            @endif
                        </td>
                        <td class="p-4 pr-6">
                            <span class="bg-emerald-100 text-emerald-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Resolved</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-8 text-center text-slate-400">Tidak ada tiket berstatus Resolved.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tampilan Mobile Section 2 -->
        <div id="containerResolvedMobile" class="md:hidden p-4 space-y-3">
            @forelse($resolvedTickets as $ticket)
            <div class="mobile-row-resolved clickable-row bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2 cursor-pointer hover:bg-slate-100/60 transition" data-url="{{ route('admin.ticket.show', $ticket->id) }}">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-800 text-xs">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">Resolved</span>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-xs">{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}</h4>
                    <span class="text-[10px] text-slate-400">Selesai: {{ $ticket->tanggal_selesai ? \Carbon\Carbon::parse($ticket->tanggal_selesai)->format('Y-m-d, H:i') : $ticket->updated_at->format('Y-m-d, H:i') }} WIB</span>
                </div>
                <div class="text-[11px] text-slate-500 space-y-0.5 pt-1">
                    <p><i class="fa-solid fa-user me-1 text-slate-400"></i>Pelapor: {{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                    <p><i class="fa-solid fa-user-gear me-1 text-slate-400"></i>PJ: {{ optional($ticket->pj)->nama_lengkap ?? $ticket->penanggung_jawab ?? '-' }}</p>
                    <p><i class="fa-solid fa-layer-group me-1 text-slate-400"></i>Prioritas: <span class="font-bold text-slate-700">{{ $ticket->prioritas }}</span></p>
                </div>
            </div>
            @empty
            <p class="text-center text-slate-400 text-xs py-4">Tidak ada tiket berstatus Resolved.</p>
            @endforelse
        </div>

        <!-- Pager Controls Section 2 (10 Items) -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 font-semibold">
            <span id="infoResolved">Menampilkan 0 - 0 dari 0 data</span>
            <div class="flex items-center gap-1.5" id="pagerResolved"></div>
        </div>
    </section>

</div>

@push('scripts')
<script>
    const limit = 10;

    function getTicketId(el) {
        const text = el.querySelector('.font-bold')?.innerText || '';
        const match = text.match(/\d+/);
        return match ? parseInt(match[0], 10) : 0;
    }

    function setupSectionPagination(desktopClass, mobileClass, desktopParentId, mobileParentId, infoId, pagerId) {
        let currentPage = 1;

        const desktopRows = Array.from(document.querySelectorAll('.' + desktopClass)).sort((a, b) => getTicketId(a) - getTicketId(b));
        const mobileRows = Array.from(document.querySelectorAll('.' + mobileClass)).sort((a, b) => getTicketId(a) - getTicketId(b));

        const desktopParent = document.getElementById(desktopParentId);
        const mobileParent = document.getElementById(mobileParentId);

        desktopRows.forEach(row => desktopParent && desktopParent.appendChild(row));
        mobileRows.forEach(row => mobileParent && mobileParent.appendChild(row));

        function render() {
            const isMobile = window.innerWidth < 768;
            const rows = isMobile ? mobileRows : desktopRows;
            const total = rows.length;
            const pages = Math.ceil(total / limit) || 1;

            if (currentPage > pages) currentPage = pages;

            const start = (currentPage - 1) * limit;
            const end = start + limit;

            desktopRows.forEach(r => r.classList.add('hidden'));
            mobileRows.forEach(r => r.classList.add('hidden'));

            rows.slice(start, end).forEach(r => r.classList.remove('hidden'));

            const infoEl = document.getElementById(infoId);
            if (infoEl) {
                infoEl.innerText = total > 0 ? `Menampilkan ${start + 1} - ${Math.min(end, total)} dari ${total} data` : `Menampilkan 0 data`;
            }

            const pagerEl = document.getElementById(pagerId);
            if (pagerEl) {
                pagerEl.innerHTML = '';
                if (pages > 1) {
                    const prev = document.createElement('button');
                    prev.className = `px-3 py-1.5 rounded-lg border border-slate-200 transition ${currentPage === 1 ? 'opacity-40 cursor-not-allowed bg-slate-100' : 'bg-white hover:bg-slate-100 cursor-pointer'}`;
                    prev.innerHTML = `<i class="fa-solid fa-chevron-left"></i>`;
                    prev.disabled = currentPage === 1;
                    prev.onclick = () => { if (currentPage > 1) { currentPage--; render(); } };
                    pagerEl.appendChild(prev);

                    const maxVisible = 5;
                    const startPage = Math.floor((currentPage - 1) / maxVisible) * maxVisible + 1;
                    const endPage = Math.min(startPage + maxVisible - 1, pages);

                    for (let i = startPage; i <= endPage; i++) {
                        const btn = document.createElement('button');
                        btn.className = `px-3 py-1.5 rounded-lg font-bold transition cursor-pointer ${i === currentPage ? 'bg-[#0a2540] text-amber-400' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100'}`;
                        btn.innerText = i;
                        btn.onclick = () => { currentPage = i; render(); };
                        pagerEl.appendChild(btn);
                    }

                    const next = document.createElement('button');
                    next.className = `px-3 py-1.5 rounded-lg border border-slate-200 transition ${currentPage === pages ? 'opacity-40 cursor-not-allowed bg-slate-100' : 'bg-white hover:bg-slate-100 cursor-pointer'}`;
                    next.innerHTML = `<i class="fa-solid fa-chevron-right"></i>`;
                    next.disabled = currentPage === pages;
                    next.onclick = () => { if (currentPage < pages) { currentPage++; render(); } };
                    pagerEl.appendChild(next);
                }
            }
        }

        window.addEventListener('resize', render);
        render();
    }

    setupSectionPagination('desktop-row-open', 'mobile-row-open', 'tbodyOpen', 'containerOpenMobile', 'infoOpen', 'pagerOpen');
    setupSectionPagination('desktop-row-resolved', 'mobile-row-resolved', 'tbodyResolved', 'containerResolvedMobile', 'infoResolved', 'pagerResolved');
</script>
@endpush
@endsection