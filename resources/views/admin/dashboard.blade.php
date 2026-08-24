@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard & Analitik')
@section('page-desc', 'Ringkasan performa dan tren penanganan tiket helpdesk ESDM')

@section('content')

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 mb-6 w-full max-w-full">
        <form id="filterForm" method="GET" action="{{ route('admin.dashboard') }}" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">
                
                <!-- Tanggal Dari -->
                <div class="min-w-0 w-full">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5 truncate">
                        <i class="fa-regular fa-calendar-minus mr-1 text-slate-400"></i> Tanggal Dari
                    </label>
                    <input type="date" name="tanggal_dari" value="{{ $filters['tanggal_dari'] ?? '' }}"
                        class="w-full max-w-full min-w-0 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 font-medium focus:bg-white focus:ring-2 focus:ring-amber-400 focus:border-transparent transition outline-none">
                </div>

                <!-- Tanggal Sampai -->
                <div class="min-w-0 w-full">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5 truncate">
                        <i class="fa-regular fa-calendar-plus mr-1 text-slate-400"></i> Tanggal Sampai
                    </label>
                    <input type="date" name="tanggal_sampai" value="{{ $filters['tanggal_sampai'] ?? '' }}"
                        class="w-full max-w-full min-w-0 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 font-medium focus:bg-white focus:ring-2 focus:ring-amber-400 focus:border-transparent transition outline-none">
                </div>

                <!-- Dropdown Status Tiket -->
                @php 
                    $statusOpts = ['All' => 'Semua Status', 'Open' => 'Open', 'In Progress' => 'In Progress', 'Resolved' => 'Resolved', 'Closed' => 'Closed', 'Dibatalkan' => 'Dibatalkan Pelapor']; 
                    $currentStatus = $filters['status'] ?? 'All';
                @endphp
                <div class="min-w-0 w-full relative custom-dropdown">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5 truncate">
                        <i class="fa-solid fa-signal mr-1 text-slate-400"></i> Status Tiket
                    </label>
                    
                    <input type="hidden" name="status" id="inputStatus" value="{{ $currentStatus }}">
                    
                    <button type="button" class="dropdown-btn w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 font-medium flex items-center justify-between transition focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none cursor-pointer">
                        <span class="dropdown-label truncate">{{ $statusOpts[$currentStatus] ?? 'Semua Status' }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                    </button>

                    <div class="dropdown-menu hidden absolute left-0 right-0 mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-1.5 space-y-0.5 max-h-60 overflow-y-auto no-scrollbar">
                        @foreach($statusOpts as $val => $label)
                            <button type="button" data-value="{{ $val }}" 
                                class="dropdown-item w-full text-left px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition flex items-center justify-between cursor-pointer {{ $currentStatus === $val ? 'bg-amber-50 text-amber-900 font-bold' : '' }}">
                                <span>{{ $label }}</span>
                                @if($currentStatus === $val)
                                    <i class="fa-solid fa-check text-amber-500 text-[10px]"></i>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Dropdown Prioritas -->
                @php 
                    $prioOpts = ['All' => 'Semua Prioritas', 'Rendah' => 'Rendah', 'Sedang' => 'Sedang', 'Tinggi' => 'Tinggi']; 
                    $currentPrio = $filters['prioritas'] ?? 'All';
                @endphp
                <div class="min-w-0 w-full relative custom-dropdown">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1.5 truncate">
                        <i class="fa-solid fa-layer-group mr-1 text-slate-400"></i> Prioritas
                    </label>

                    <input type="hidden" name="prioritas" id="inputPrioritas" value="{{ $currentPrio }}">

                    <button type="button" class="dropdown-btn w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-700 font-medium flex items-center justify-between transition focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none cursor-pointer">
                        <span class="dropdown-label truncate">{{ $prioOpts[$currentPrio] ?? 'Semua Prioritas' }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                    </button>

                    <div class="dropdown-menu hidden absolute left-0 right-0 mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-1.5 space-y-0.5 max-h-60 overflow-y-auto no-scrollbar">
                        @foreach($prioOpts as $val => $label)
                            <button type="button" data-value="{{ $val }}" 
                                class="dropdown-item w-full text-left px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition flex items-center justify-between cursor-pointer {{ $currentPrio === $val ? 'bg-amber-50 text-amber-900 font-bold' : '' }}">
                                <span>{{ $label }}</span>
                                @if($currentPrio === $val)
                                    <i class="fa-solid fa-check text-amber-500 text-[10px]"></i>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Kategori Checkbox Pills -->
            <div class="pt-3 border-t border-slate-100">
                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">
                    <i class="fa-solid fa-tags mr-1 text-slate-400"></i> Filter Kategori
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach($daftarKategori as $kat)
                        @php $isChecked = in_array($kat, $filters['kategori'] ?? []); @endphp
                        <label class="cursor-pointer max-w-full">
                            <input type="checkbox" name="kategori[]" value="{{ $kat }}" {{ $isChecked ? 'checked' : '' }} class="peer hidden">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 transition peer-checked:bg-amber-400 peer-checked:border-amber-400 peer-checked:text-[#0a2540] peer-checked:shadow-sm hover:bg-slate-100 break-all">
                                <i class="fa-solid fa-check text-[10px] hidden peer-checked:inline-block"></i>
                                {{ $kat }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-2">
                <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto text-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs px-5 py-2.5 rounded-xl transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-rotate-right"></i> Reset
                </a>
                <button type="submit" class="w-full sm:w-auto bg-[#0a2540] hover:bg-slate-800 text-white font-bold text-xs px-6 py-2.5 rounded-xl transition shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-filter"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- ===== KPI CARDS ===== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Total Tiket -->
        <div class="relative overflow-hidden bg-gradient-to-br from-[#0a2540] to-[#12385f] text-white rounded-2xl p-5 shadow-sm min-w-0">
            <div class="flex items-start justify-between">
                <div class="min-w-0">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-300 block truncate">Total Tiket</span>
                    <p class="text-3xl font-black mt-1 truncate text-amber-400">{{ number_format($totalTiket) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-400 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-ticket"></i>
                </div>
            </div>
            <p class="text-[11px] text-slate-300/80 mt-3 flex items-center gap-1 truncate">
                <i class="fa-solid fa-circle-info text-[10px]"></i> Total tiket pada periode filter
            </p>
        </div>

        <!-- Card 2: Avg Resolution -->
        <div class="relative overflow-hidden bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm min-w-0">
            <div class="flex items-start justify-between">
                <div class="min-w-0">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block truncate">Rata-rata Waktu Solusi</span>
                    <p class="text-3xl font-black text-slate-800 mt-1 truncate">{{ $avgResolutionDays }} <span class="text-sm font-semibold text-slate-500">Hari</span></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3 flex items-center gap-1 truncate">
                <i class="fa-solid fa-bolt text-amber-500 text-[10px]"></i> Estimasi pengerjaan tiket
            </p>
        </div>

        <!-- Card 3: SLA Compliance -->
        <div class="relative overflow-hidden bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm min-w-0">
            <div class="flex items-start justify-between">
                <div class="min-w-0">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block truncate">Kepatuhan SLA</span>
                    <p class="text-3xl font-black text-emerald-600 mt-1 truncate">{{ $slaCompliance }}<span class="text-lg font-bold">%</span></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full" style="width: {{ min(100, max(0, $slaCompliance)) }}%"></div>
            </div>
        </div>

        <!-- Card 4: Overdue % -->
        <div class="relative overflow-hidden bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm min-w-0">
            <div class="flex items-start justify-between">
                <div class="min-w-0">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block truncate">Tiket Overdue</span>
                    <p class="text-3xl font-black text-rose-500 mt-1 truncate">{{ $persenOverdue }}<span class="text-lg font-bold">%</span></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-rose-500 h-full rounded-full" style="width: {{ min(100, max(0, $persenOverdue)) }}%"></div>
            </div>
        </div>
    </div>

    <!-- ===== ROW 1: Bar Kategori + Line Bulan ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 lg:p-6 flex flex-col justify-between min-w-0">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-simple text-amber-500"></i> Total Tiket by Kategori
                </h3>
            </div>
            <div class="relative w-full h-[250px] min-w-0">
                <canvas id="chartKategori"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 lg:p-6 flex flex-col justify-between min-w-0">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-[#0a2540]"></i> Tren Tiket per Bulan
                </h3>
            </div>
            <div class="relative w-full h-[250px] min-w-0">
                <canvas id="chartBulan"></canvas>
            </div>
        </div>
    </div>

    <!-- ===== ROW 2: Donut + Tabel ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 lg:p-6 flex flex-col justify-between min-w-0">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-indigo-500"></i> Status Overdue vs On Time
                </h3>
            </div>
            <div class="relative w-full h-[230px] flex items-center justify-center min-w-0">
                <canvas id="chartOverdue"></canvas>
            </div>
        </div>

        <!-- Tabel Rekap -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 lg:p-6 min-w-0">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-table-cells text-emerald-500"></i> Rekap Tiket per Bulan & Hari
                </h3>
            </div>
            
            <div class="overflow-x-auto rounded-xl border border-slate-100 max-w-full no-scrollbar">
                <table class="w-full text-left border-collapse text-xs whitespace-nowrap sm:whitespace-normal">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold border-b border-slate-100 uppercase tracking-wider">
                            <th class="p-3">Bulan</th>
                            @foreach($days as $d)
                                <th class="p-3 text-center">{{ substr($d, 0, 3) }}</th>
                            @endforeach
                            <th class="p-3 text-center font-black bg-amber-50 text-amber-900">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                        @forelse($tabelBulanHari as $row)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-3 font-bold text-slate-800 bg-slate-50/30">{{ $row->bulan }}</td>
                            @foreach($days as $d)
                                <td class="p-3 text-center">{{ $row->$d }}</td>
                            @endforeach
                            <td class="p-3 text-center font-bold text-[#0a2540] bg-amber-50/50">{{ $row->total }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($days) + 2 }}" class="p-8 text-center text-slate-400">
                                <i class="fa-regular fa-folder-open text-2xl block mb-2"></i>
                                Tidak ada data pada rentang filter ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="chartData"
        data-kategori-labels='@json($tiketByKategori->pluck("kategori"))'
        data-kategori-total='@json($tiketByKategori->pluck("total"))'
        data-bulan-labels='@json($tiketByBulan->pluck("bulan"))'
        data-bulan-total='@json($tiketByBulan->pluck("total"))'
        data-overdue="{{ $donutOverdue }}"
        data-ontime="{{ $donutOnTime }}"
        class="hidden"
    ></div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
    document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const btn = dropdown.querySelector('.dropdown-btn');
        const menu = dropdown.querySelector('.dropdown-menu');
        const label = dropdown.querySelector('.dropdown-label');
        const input = dropdown.querySelector('input[type="hidden"]');
        const arrow = btn.querySelector('.fa-chevron-down');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.dropdown-menu').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });
            document.querySelectorAll('.fa-chevron-down').forEach(a => {
                if (a !== arrow) a.classList.remove('rotate-180');
            });

            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });

        dropdown.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', () => {
                const selectedVal = item.getAttribute('data-value');
                const selectedText = item.querySelector('span').innerText;

                input.value = selectedVal;
                label.innerText = selectedText;

                dropdown.querySelectorAll('.dropdown-item').forEach(i => {
                    i.classList.remove('bg-amber-50', 'text-amber-900', 'font-bold');
                    const check = i.querySelector('.fa-check');
                    if (check) check.remove();
                });

                item.classList.add('bg-amber-50', 'text-amber-900', 'font-bold');
                item.insertAdjacentHTML('beforeend', '<i class="fa-solid fa-check text-amber-500 text-[10px]"></i>');

                menu.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            });
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.fa-chevron-down').forEach(a => a.classList.remove('rotate-180'));
    });

    const el = document.getElementById('chartData');
    const kategoriLabels = JSON.parse(el.dataset.kategoriLabels || '[]');
    const kategoriTotal  = JSON.parse(el.dataset.kategoriTotal || '[]');
    const bulanLabels    = JSON.parse(el.dataset.bulanLabels || '[]');
    const bulanTotal     = JSON.parse(el.dataset.bulanTotal || '[]');
    const donutOverdue   = Number(el.dataset.overdue || 0);
    const donutOnTime    = Number(el.dataset.ontime || 0);

    const barColors = ['#f59e0b', '#0a2540', '#10b981', '#3b82f6', '#ec4899', '#8b5cf6', '#06b6d4'];

    // 1. Chart Kategori
    new Chart(document.getElementById('chartKategori'), {
        type: 'bar',
        data: {
            labels: kategoriLabels,
            datasets: [{
                label: 'Total Tiket',
                data: kategoriTotal,
                backgroundColor: barColors,
                borderRadius: 8,
                barThickness: 16
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 10 } }
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { size: 11, weight: '600' }, color: '#475569' }
                }
            }
        }
    });

    // 2. Chart Bulan (Navy Area Gradient)
    new Chart(document.getElementById('chartBulan'), {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Total Tiket',
                data: bulanTotal,
                fill: true,
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
                    gradient.addColorStop(0, 'rgba(10, 37, 64, 0.35)');
                    gradient.addColorStop(1, 'rgba(10, 37, 64, 0.0)');
                    return gradient;
                },
                borderColor: '#0a2540',
                borderWidth: 2.5,
                tension: 0.35,
                pointBackgroundColor: '#f59e0b',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } }
            }
        }
    });

    // 3. Chart Overdue
    new Chart(document.getElementById('chartOverdue'), {
        type: 'doughnut',
        data: {
            labels: ['Overdue', 'On Time'],
            datasets: [{
                data: [donutOverdue, donutOnTime],
                backgroundColor: ['#f43f5e', '#0a2540'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 10, usePointStyle: true, font: { size: 11, weight: '600' }, padding: 15 }
                }
            }
        }
    });
</script>
@endpush
@endsection