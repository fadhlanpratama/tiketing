@extends('admin.layout')

@section('title', 'Semua Tiket')
@section('page-title', 'Seluruh Tiket Helpdesk')
@section('page-desc', 'Rekap semua tiket dari seluruh status')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">

    <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Seluruh Tiket Helpdesk</h3>
            <p class="text-xs text-slate-400">Klik baris untuk melihat detail.</p>
        </div>

        <div class="relative w-full sm:w-52" id="statusFilterWrapperAdmin">
            <button type="button" id="statusFilterBtnAdmin"
                class="w-full bg-slate-50 border border-slate-200 p-2.5 pr-10 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition-all text-xs font-semibold text-slate-600 flex justify-between items-center cursor-pointer hover:bg-slate-100/60">
                <span id="statusFilterLabelAdmin"><i class="fa-solid fa-filter mr-1.5 text-slate-400"></i>Semua Status</span>
                <svg id="statusFilterArrowAdmin" class="h-4 w-4 text-slate-400 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div id="statusFilterMenuAdmin" class="hidden absolute right-0 sm:left-0 z-50 mt-1 w-full bg-white border border-slate-200 shadow-2xl rounded-xl p-1 space-y-0.5 text-xs text-slate-700 font-medium">
                <div data-value="semua" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Semua Status</div>
                <div data-value="Open" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Open</div>
                <div data-value="In Progress" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">In Progress</div>
                <div data-value="Resolved" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Resolved</div>
                <div data-value="Closed" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Closed</div>
                <div data-value="Dibatalkan" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Dibatalkan oleh Pelapor</div>
            </div>
        </div>
    </div>

    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 font-semibold text-xs tracking-wider border-b border-slate-100">
                    <th class="p-4 pl-6">ID TIKET</th>
                    <th class="p-4">KATEGORI PERMINTAAN</th>
                    <th class="p-4">PELAPOR</th>
                    <th class="p-4">PJ TEKNISI</th>
                    <th class="p-4">PRIORITAS</th>
                    <th class="p-4 pr-6">STATUS</th>
                </tr>
            </thead>
            <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                @forelse($allTickets as $ticket)
                <tr class="clickable-row hover:bg-slate-50/50 transition cursor-pointer" data-url="{{ route('admin.ticket.show', $ticket->id) }}" data-status="{{ $ticket->status }}" data-closedby="{{ $ticket->closed_by }}">
                    <td class="p-4 pl-6 font-semibold text-slate-700">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="p-4">
                        <p class="font-medium text-slate-800">{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}</p>
                        <span class="text-[11px] text-slate-400">Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</span>
                    </td>
                    <td class="p-4">
                        <p class="font-medium text-slate-800">{{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                        <span class="text-[11px] text-slate-400">{{ $ticket->pelapor->divisi ?? '-' }}</span>
                    </td>
                    <td class="p-4 text-slate-600">{{ optional($ticket->pj)->nama_lengkap ?? $ticket->penanggung_jawab ?? '-' }}</td>
                    <td class="p-4">
                        @if(strtolower($ticket->prioritas) == 'tinggi')
                            <span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded-lg text-xs border border-red-100"><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $ticket->prioritas }}</span>
                        @elseif(strtolower($ticket->prioritas) == 'sedang')
                            <span class="text-amber-600 font-bold bg-amber-50 px-2 py-1 rounded-lg text-xs border border-amber-100"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $ticket->prioritas }}</span>
                        @else
                            <span class="text-slate-600 font-bold bg-slate-100 px-2 py-1 rounded-lg text-xs"><i class="fa-solid fa-circle-info mr-1"></i>{{ $ticket->prioritas }}</span>
                        @endif
                    </td>
                    <td class="p-4 pr-6">
                        @if($ticket->status == 'Open')
                            <span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Open</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">In Progress</span>
                        @elseif($ticket->status == 'Resolved')
                            <span class="bg-green-100 text-green-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Resolved</span>
                        @elseif($ticket->status == 'Closed')
                            <span class="bg-slate-100 text-slate-600 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Closed</span>
                            @if($ticket->closed_by === 'user')
                                <span class="text-[10px] text-slate-600 italic block mt-1"><i class="fa-solid fa-user-slash"></i> Dibatalkan oleh Pelapor</span>
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

    <div class="md:hidden p-4 space-y-4">
        @forelse($allTickets as $ticket)
        <div class="clickable-row bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-3 cursor-pointer" data-url="{{ route('admin.ticket.show', $ticket->id) }}" data-status="{{ $ticket->status }}" data-closedby="{{ $ticket->closed_by }}">
            <div class="flex justify-between items-center">
                <span class="font-bold text-slate-700">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                @if($ticket->status == 'Open')
                    <span class="bg-amber-100 text-amber-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Open</span>
                @elseif($ticket->status == 'In Progress')
                    <span class="bg-blue-100 text-blue-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">In Progress</span>
                @elseif($ticket->status == 'Resolved')
                    <span class="bg-green-100 text-green-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Resolved</span>
                @elseif($ticket->status == 'Closed')
                    <span class="bg-slate-100 text-slate-600 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Closed</span>
                @endif
            </div>
            @if($ticket->status == 'Closed' && $ticket->closed_by === 'user')
                <p class="text-[10px] text-slate-600 italic"><i class="fa-solid fa-user-slash"></i> Dibatalkan oleh Pelapor</p>
            @endif
            <div>
                <h4 class="font-bold text-slate-800 text-sm">{{ $ticket->kategori }}</h4>
                <p class="text-xs text-slate-600 mt-0.5">{{ $ticket->sub_kategori }}</p>
                <p class="text-[11px] text-slate-400 mt-2"><i class="fa-solid fa-user"></i> Pelapor: {{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5"><i class="fa-solid fa-user-gear"></i> PJ: {{ $ticket->penanggung_jawab ?? 'Belum ditunjuk' }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5"><i class="fa-regular fa-clock"></i> Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</p>
                <p class="text-[10px] text-slate-500 font-medium mt-1">
                    <i class="fa-solid fa-layer-group mr-1 text-slate-400"></i>Prioritas:
                    @if(strtolower($ticket->prioritas) == 'tinggi')
                        <span class="text-red-600 font-bold">Tinggi</span>
                    @elseif(strtolower($ticket->prioritas) == 'sedang')
                        <span class="text-amber-600 font-bold">Sedang</span>
                    @else
                        <span class="text-slate-700 font-bold">Rendah</span>
                    @endif
                </p>
            </div>
        </div>
        @empty
        <p class="text-center text-slate-400 text-sm py-4">Belum ada tiket sama sekali.</p>
        @endforelse
    </div>

</div>

@push('scripts')
<script>
    const statusFilterBtnAdmin = document.getElementById('statusFilterBtnAdmin');
    const statusFilterMenuAdmin = document.getElementById('statusFilterMenuAdmin');
    const statusFilterArrowAdmin = document.getElementById('statusFilterArrowAdmin');
    const statusFilterLabelAdmin = document.getElementById('statusFilterLabelAdmin');

    if (statusFilterBtnAdmin) {
        statusFilterBtnAdmin.addEventListener('click', (e) => {
            e.stopPropagation();
            statusFilterMenuAdmin.classList.toggle('hidden');
            statusFilterArrowAdmin.classList.toggle('rotate-180');
        });

        document.querySelectorAll('.status-filter-item-admin').forEach(item => {
            item.addEventListener('click', () => {
                const value = item.getAttribute('data-value');
                statusFilterLabelAdmin.innerHTML = `<i class="fa-solid fa-filter mr-1.5 text-slate-400"></i>${item.innerText}`;
                statusFilterMenuAdmin.classList.add('hidden');
                statusFilterArrowAdmin.classList.remove('rotate-180');

                document.querySelectorAll('.clickable-row').forEach(row => {
                    const status = row.getAttribute('data-status');
                    const closedBy = row.getAttribute('data-closedby');
                    let show = true;
                    if (value === 'semua') show = true;
                    else if (value === 'Dibatalkan') show = (status === 'Closed' && closedBy === 'user');
                    else if (value === 'Closed') show = (status === 'Closed' && closedBy !== 'user');
                    else show = (status === value);
                    row.classList.toggle('hidden', !show);
                });
            });
        });

        document.addEventListener('click', () => {
            statusFilterMenuAdmin.classList.add('hidden');
            statusFilterArrowAdmin.classList.remove('rotate-180');
        });
        statusFilterMenuAdmin.addEventListener('click', (e) => e.stopPropagation());
    }
</script>
@endpush
@endsection