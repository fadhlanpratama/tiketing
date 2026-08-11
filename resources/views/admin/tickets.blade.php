@extends('admin.layout')

@section('title', 'Manajemen Tiket')
@section('page-title', 'Manajemen Tiket Helpdesk')
@section('page-desc', 'Penugasan PJ dan verifikasi tiket yang siap ditutup')

@section('content')
<div class="space-y-8">

    <section class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-user-gear text-[#0a2540]"></i>
                <h3 class="font-bold text-slate-800 text-base">Penugasan PJ (Tiket Open)</h3>
            </div>
            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full">
                {{ $unassignedTickets->count() }} Belum Ditunjuk PJ
            </span>
        </div>
        <p class="text-[11px] text-slate-400 -mt-2">Klik baris tiket untuk melihat detail lengkap.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-semibold border-b border-slate-100">
                        <th class="p-3.5">ID TIKET</th>
                        <th class="p-3.5">PELAPOR</th>
                        <th class="p-3.5">KATEGORI</th>
                        <th class="p-3.5">PRIORITAS</th>
                        <th class="p-3.5">PILIH PJ / TEKNISI</th>
                        <th class="p-3.5 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-slate-600 divide-y divide-slate-100">
                    @forelse($unassignedTickets as $ticket)
                    <tr class="clickable-row cursor-pointer hover:bg-slate-50 transition" data-url="{{ route('admin.ticket.show', $ticket->id) }}">
                        <td class="p-3.5 font-bold text-slate-800">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-3.5 font-bold">{{ $ticket->pelapor->nama_lengkap ?? 'User' }}</td>
                        <td class="p-3.5">{{ $ticket->kategori }}</td>
                        <td class="p-3.5"><span class="px-2 py-1 rounded text-[10px] font-bold border border-amber-200 bg-amber-50 text-amber-600">{{ $ticket->prioritas }}</span></td>
                        <form action="{{ route('admin.ticket.assign', $ticket->id) }}" method="POST" class="action-buttons contents">
                            @csrf
                            <td class="p-3.5">
                                <select name="penanggung_jawab" required class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs w-full focus:outline-none focus:border-amber-500">
                                    <option value="" disabled selected>-- Pilih PJ --</option>
                                    @foreach($activePjs as $pj)
                                        <option value="{{ $pj->id }}">{{ $pj->nama_lengkap }} ({{ $pj->divisi }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-3.5 text-center">
                                <button type="submit" class="bg-[#0a2540] hover:bg-slate-800 text-white font-bold px-4 py-2 rounded-lg transition">
                                    Tunjuk PJ
                                </button>
                            </td>
                        </form>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-8 text-center text-slate-400">Tidak ada tiket yang butuh penunjukan PJ.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-600"></i>
                <h3 class="font-bold text-slate-800 text-base">Tiket Resolved (Siap Diclosed Admin)</h3>
            </div>
            <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full">
                {{ $resolvedTickets->count() }} Siap Verifikasi
            </span>
        </div>
        <p class="text-[11px] text-slate-400 -mt-2">Klik baris tiket untuk melihat detail lengkap.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-xs font-semibold border-b border-slate-100">
                        <th class="p-3.5">ID TIKET</th>
                        <th class="p-3.5">PELAPOR</th>
                        <th class="p-3.5">PJ TEKNISI</th>
                        <th class="p-3.5">BUKTI FOTO</th>
                        <th class="p-3.5 text-center">AKSI UBAH CLOSED</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-slate-600 divide-y divide-slate-100">
                    @forelse($resolvedTickets as $ticket)
                    <tr class="clickable-row cursor-pointer hover:bg-slate-50 transition" data-url="{{ route('admin.ticket.show', $ticket->id) }}">
                        <td class="p-3.5 font-bold text-slate-800">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-3.5 font-bold">{{ $ticket->pelapor->nama_lengkap ?? 'User' }}</td>
                        <td class="p-3.5 text-slate-800 font-semibold">{{ optional($ticket->pj)->nama_lengkap ?? $ticket->penanggung_jawab }}</td>
                        <td class="p-3.5 action-buttons">
                            @if($ticket->hasil_resolved_foto)
                                <a href="{{ asset('storage/' . $ticket->hasil_resolved_foto) }}" target="_blank" class="text-amber-600 hover:underline font-bold">
                                    <i class="fa-solid fa-image me-1"></i>Lihat Foto
                                </a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-center action-buttons">
                            <form action="{{ route('admin.ticket.close', $ticket->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Ubah status tiket menjadi Closed?')" class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-lg transition">
                                    <i class="fa-solid fa-lock me-1"></i> Ubah Ke Closed
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-8 text-center text-slate-400">Tidak ada tiket berstatus Resolved.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection