@extends('admin.layout')

@section('title', 'Semua Tiket')
@section('page-title', 'Seluruh Tiket Helpdesk')
@section('page-desc', 'Rekap semua tiket dari seluruh status penanganan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-visible">

    <!-- Header & Filter (Status & Prioritas) -->
    <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-800">Seluruh Tiket Helpdesk</h3>
            <p class="text-xs text-slate-400">Klik baris untuk melihat detail lengkap tiket.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            
            <div class="relative w-full sm:w-52 custom-dropdown" id="statusFilterWrapperAdmin">
                <button type="button" class="dropdown-btn w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl text-xs font-semibold text-slate-600 flex justify-between items-center cursor-pointer hover:bg-slate-100/80 transition focus:ring-2 focus:ring-amber-400 outline-none">
                    <span class="dropdown-label truncate"><i class="fa-solid fa-signal mr-1.5 text-slate-400"></i>Semua Status</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="dropdown-menu hidden absolute right-0 z-50 mt-1.5 w-full bg-white border border-slate-200 shadow-2xl rounded-2xl p-1.5 space-y-0.5 text-xs text-slate-700 font-medium no-scrollbar">
                    <div data-filter-type="status" data-value="semua" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between bg-amber-50 text-amber-900 font-bold">
                        <span>Semua Status</span>
                    </div>
                    <div data-filter-type="status" data-value="Open" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between">
                        <span>Open</span>
                    </div>
                    <div data-filter-type="status" data-value="In Progress" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between">
                        <span>In Progress</span>
                    </div>
                    <div data-filter-type="status" data-value="Resolved" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between">
                        <span>Resolved</span>
                    </div>
                    <div data-filter-type="status" data-value="Closed" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between">
                        <span>Closed</span>
                    </div>
                    <div data-filter-type="status" data-value="Dibatalkan" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between">
                        <span>Dibatalkan oleh Pelapor</span>
                    </div>
                </div>
            </div>

            <div class="relative w-full sm:w-52 custom-dropdown" id="prioFilterWrapperAdmin">
                <button type="button" class="dropdown-btn w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl text-xs font-semibold text-slate-600 flex justify-between items-center cursor-pointer hover:bg-slate-100/80 transition focus:ring-2 focus:ring-amber-400 outline-none">
                    <span class="dropdown-label truncate"><i class="fa-solid fa-layer-group mr-1.5 text-slate-400"></i>Semua Prioritas</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="dropdown-menu hidden absolute right-0 z-50 mt-1.5 w-full bg-white border border-slate-200 shadow-2xl rounded-2xl p-1.5 space-y-0.5 text-xs text-slate-700 font-medium no-scrollbar">
                    <div data-filter-type="prioritas" data-value="semua" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between bg-amber-50 text-amber-900 font-bold">
                        <span>Semua Prioritas</span>
                    </div>
                    <div data-filter-type="prioritas" data-value="Rendah" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between">
                        <span>Rendah</span>
                    </div>
                    <div data-filter-type="prioritas" data-value="Sedang" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between">
                        <span>Sedang</span>
                    </div>
                    <div data-filter-type="prioritas" data-value="Tinggi" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between">
                        <span>Tinggi</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Tabel Desktop -->
    <div class="hidden md:block overflow-x-auto no-scrollbar rounded-b-2xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 font-bold text-xs tracking-wider uppercase border-b border-slate-100">
                    <th class="p-4 pl-6">ID TIKET</th>
                    <th class="p-4">KATEGORI PERMINTAAN</th>
                    <th class="p-4">PELAPOR</th>
                    <th class="p-4">PJ TEKNISI</th>
                    <th class="p-4">PRIORITAS</th>
                    <th class="p-4 pr-6">STATUS</th>
                </tr>
            </thead>
            <tbody id="ticketTableBody" class="text-sm text-slate-600 divide-y divide-slate-100">
                @forelse($allTickets as $ticket)
                <tr class="desktop-row clickable-row hover:bg-slate-50/80 transition cursor-pointer" data-url="{{ route('admin.ticket.show', $ticket->id) }}" data-status="{{ $ticket->status }}" data-closedby="{{ $ticket->closed_by }}" data-prioritas="{{ $ticket->prioritas }}">
                    <td class="p-4 pl-6 font-bold text-slate-800">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="p-4">
                        <p class="font-bold text-slate-800">{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}</p>
                        <span class="text-[11px] text-slate-400">Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</span>
                    </td>
                    <td class="p-4">
                        <p class="font-bold text-slate-800">{{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                        <span class="text-[11px] text-slate-400">{{ $ticket->pelapor->divisi ?? '-' }}</span>
                    </td>
                    <td class="p-4"> 
                        <p class="font-semibold text-slate-700">{{ optional($ticket->pj)->nama_lengkap ?? $ticket->penanggung_jawab ?? '-' }}</p>
                        <span class="text-[11px] text-slate-400">{{ optional($ticket->pj)->divisi ?? '-' }}</span>
                    </td>
                    <td class="p-4">
                        @if(strtolower($ticket->prioritas) == 'tinggi')
                            <span class="text-rose-600 font-bold bg-rose-50 px-2.5 py-1 rounded-lg text-xs border border-rose-100"><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $ticket->prioritas }}</span>
                        @elseif(strtolower($ticket->prioritas) == 'sedang')
                            <span class="text-amber-700 font-bold bg-amber-50 px-2.5 py-1 rounded-lg text-xs border border-amber-100"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $ticket->prioritas }}</span>
                        @else
                            <span class="text-slate-600 font-bold bg-slate-100 px-2.5 py-1 rounded-lg text-xs"><i class="fa-solid fa-circle-info mr-1"></i>{{ $ticket->prioritas }}</span>
                        @endif
                    </td>
                    <td class="p-4 pr-6">
                        @if($ticket->status == 'Open')
                            <span class="bg-amber-100 text-[#0a2540] text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Open</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">In Progress</span>
                        @elseif($ticket->status == 'Resolved')
                            <span class="bg-emerald-100 text-emerald-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Resolved</span>
                        @elseif($ticket->status == 'Closed')
                            <span class="bg-slate-100 text-slate-600 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Closed</span>
                            @if($ticket->closed_by === 'user')
                                <span class="text-[10px] text-slate-500 italic block mt-1"><i class="fa-solid fa-user-slash"></i> Dibatalkan Pelapor</span>
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-slate-400 text-sm">Belum ada tiket sama sekali.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tampilan Mobile -->
    <div id="ticketMobileContainer" class="md:hidden p-4 space-y-3">
        @forelse($allTickets as $ticket)
        <div class="mobile-row clickable-row bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2.5 cursor-pointer hover:bg-slate-100/60 transition" data-url="{{ route('admin.ticket.show', $ticket->id) }}" data-status="{{ $ticket->status }}" data-closedby="{{ $ticket->closed_by }}" data-prioritas="{{ $ticket->prioritas }}">
            <div class="flex justify-between items-center">
                <span class="font-bold text-slate-800 text-xs">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                @if($ticket->status == 'Open')
                    <span class="bg-amber-100 text-[#0a2540] text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Open</span>
                @elseif($ticket->status == 'In Progress')
                    <span class="bg-blue-100 text-blue-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">In Progress</span>
                @elseif($ticket->status == 'Resolved')
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Resolved</span>
                @elseif($ticket->status == 'Closed')
                    <span class="bg-slate-100 text-slate-600 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Closed</span>
                @endif
            </div>
            @if($ticket->status == 'Closed' && $ticket->closed_by === 'user')
                <p class="text-[10px] text-slate-500 italic"><i class="fa-solid fa-user-slash"></i> Dibatalkan oleh Pelapor</p>
            @endif
            <div>
                <h4 class="font-bold text-slate-800 text-xs">{{ $ticket->kategori }} — <span class="font-normal text-slate-600">{{ $ticket->sub_kategori }}</span></h4>
                <div class="text-[11px] text-slate-500 mt-2 space-y-0.5">
                    <p><i class="fa-solid fa-user me-1 text-slate-400"></i>Pelapor: {{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                    <p><i class="fa-solid fa-user-gear me-1 text-slate-400"></i>PJ: {{ optional($ticket->pj)->nama_lengkap ?? $ticket->penanggung_jawab ?? 'Belum ditunjuk' }}</p>
                    <p><i class="fa-solid fa-layer-group me-1 text-slate-400"></i>Prioritas: <span class="font-bold text-slate-700">{{ $ticket->prioritas }}</span></p>
                    <p><i class="fa-regular fa-clock me-1 text-slate-400"></i>Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</p>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-slate-400 text-xs py-4">Belum ada tiket sama sekali.</p>
        @endforelse
    </div>

    <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 font-semibold rounded-b-2xl">
        <span id="pageInfo">Menampilkan 0 - 0 dari 0 data</span>
        <div class="flex items-center gap-1.5" id="paginationControls">
        </div>
    </div>

</div>

@push('scripts')
<script>
    const itemsPerPage = 8;
    let currentPage = 1;

    let selectedStatusFilter = 'semua';
    let selectedPrioFilter = 'semua';

    document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const btn = dropdown.querySelector('.dropdown-btn');
        const menu = dropdown.querySelector('.dropdown-menu');
        const arrow = btn.querySelector('.fa-chevron-down');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.dropdown-menu').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.fa-chevron-down').forEach(a => a.classList.remove('rotate-180'));
    });

    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', (e) => {
            if (!e.target.closest('button') && !e.target.closest('a')) {
                const url = row.getAttribute('data-url');
                if (url) window.location.href = url;
            }
        });
    });

    function getTicketId(el) {
        const text = el.querySelector('.font-bold')?.innerText || '';
        const match = text.match(/\d+/);
        return match ? parseInt(match[0], 10) : 0;
    }

    const desktopRows = Array.from(document.querySelectorAll('.desktop-row')).sort((a, b) => getTicketId(a) - getTicketId(b));
    const mobileRows = Array.from(document.querySelectorAll('.mobile-row')).sort((a, b) => getTicketId(a) - getTicketId(b));

    const desktopTbody = document.getElementById('ticketTableBody');
    const mobileContainer = document.getElementById('ticketMobileContainer');

    desktopRows.forEach(row => desktopTbody && desktopTbody.appendChild(row));
    mobileRows.forEach(row => mobileContainer && mobileContainer.appendChild(row));

    function checkFilter(row) {
        const status = row.getAttribute('data-status');
        const closedBy = row.getAttribute('data-closedby');
        const prioritas = (row.getAttribute('data-prioritas') || '').toLowerCase();

        let matchStatus = false;
        if (selectedStatusFilter === 'semua') matchStatus = true;
        else if (selectedStatusFilter === 'Dibatalkan') matchStatus = (status === 'Closed' && closedBy === 'user');
        else if (selectedStatusFilter === 'Closed') matchStatus = (status === 'Closed' && closedBy !== 'user');
        else matchStatus = (status === selectedStatusFilter);

        let matchPrio = false;
        if (selectedPrioFilter === 'semua') matchPrio = true;
        else matchPrio = (prioritas === selectedPrioFilter.toLowerCase());

        return matchStatus && matchPrio;
    }

    function renderPage() {
        const isMobile = window.innerWidth < 768;
        const activeRows = isMobile ? mobileRows : desktopRows;

        const filtered = activeRows.filter(row => checkFilter(row));
        const totalItems = filtered.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;

        if (currentPage > totalPages) currentPage = totalPages;

        const startIdx = (currentPage - 1) * itemsPerPage;
        const endIdx = startIdx + itemsPerPage;

        desktopRows.forEach(row => row.classList.add('hidden'));
        mobileRows.forEach(row => row.classList.add('hidden'));

        filtered.slice(startIdx, endIdx).forEach(row => row.classList.remove('hidden'));

        const pageInfo = document.getElementById('pageInfo');
        if (totalItems > 0) {
            pageInfo.innerText = `Menampilkan ${startIdx + 1} - ${Math.min(endIdx, totalItems)} dari ${totalItems} data`;
        } else {
            pageInfo.innerText = `Menampilkan 0 data`;
        }

        const paginationControls = document.getElementById('paginationControls');
        paginationControls.innerHTML = '';

        if (totalPages > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.className = `px-3 py-1.5 rounded-lg border border-slate-200 transition ${currentPage === 1 ? 'opacity-40 cursor-not-allowed bg-slate-100' : 'bg-white hover:bg-slate-100 cursor-pointer'}`;
            prevBtn.innerHTML = `<i class="fa-solid fa-chevron-left"></i>`;
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderPage(); } };
            paginationControls.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const pBtn = document.createElement('button');
                pBtn.className = `px-3 py-1.5 rounded-lg font-bold transition cursor-pointer ${i === currentPage ? 'bg-[#0a2540] text-amber-400' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100'}`;
                pBtn.innerText = i;
                pBtn.onclick = () => { currentPage = i; renderPage(); };
                paginationControls.appendChild(pBtn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.className = `px-3 py-1.5 rounded-lg border border-slate-200 transition ${currentPage === totalPages ? 'opacity-40 cursor-not-allowed bg-slate-100' : 'bg-white hover:bg-slate-100 cursor-pointer'}`;
            nextBtn.innerHTML = `<i class="fa-solid fa-chevron-right"></i>`;
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderPage(); } };
            paginationControls.appendChild(nextBtn);
        }
    }

    window.addEventListener('resize', renderPage);

    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            const filterType = item.getAttribute('data-filter-type');
            const value = item.getAttribute('data-value');
            const labelText = item.querySelector('span').innerText;

            const dropdownContainer = item.closest('.custom-dropdown');
            const dropdownLabel = dropdownContainer.querySelector('.dropdown-label');

            if (filterType === 'status') {
                selectedStatusFilter = value;
                dropdownLabel.innerHTML = `<i class="fa-solid fa-signal mr-1.5 text-slate-400"></i>${labelText}`;
            } else if (filterType === 'prioritas') {
                selectedPrioFilter = value;
                dropdownLabel.innerHTML = `<i class="fa-solid fa-layer-group mr-1.5 text-slate-400"></i>${labelText}`;
            }

            dropdownContainer.querySelectorAll('.dropdown-item').forEach(i => {
                i.classList.remove('bg-amber-50', 'text-amber-900', 'font-bold');
            });
            item.classList.add('bg-amber-50', 'text-amber-900', 'font-bold');

            dropdownContainer.querySelector('.dropdown-menu').classList.add('hidden');
            dropdownContainer.querySelector('.fa-chevron-down').classList.remove('rotate-180');

            currentPage = 1;
            renderPage();
        });
    });

    renderPage();
</script>
@endpush
@endsection