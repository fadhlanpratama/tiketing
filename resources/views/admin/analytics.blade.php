<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Analitik Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
</head>
<body class="bg-slate-100 font-sans antialiased min-h-screen flex">

    <!-- ================= SIDEBAR ================= -->
    <aside class="w-64 bg-[#0a2540] text-white flex flex-col shrink-0 min-h-screen shadow-xl sticky top-0 h-screen">
        <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-700/60 shrink-0">
            <img src="{{ asset('image/esdm.png') }}" alt="Logo" class="w-9 h-9 object-contain">
            <div>
                <h1 class="font-black text-sm tracking-wider">ADMIN PANEL</h1>
                <span class="text-[9px] text-amber-400 uppercase font-bold tracking-widest block">Sistem Tiketing</span>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition text-slate-300 hover:bg-slate-800 hover:text-white">
                <i class="fa-solid fa-user-check text-sm"></i>
                <span>Persetujuan Akun</span>
            </a>

            <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition text-slate-300 hover:bg-slate-800 hover:text-white">
                <i class="fa-solid fa-ticket text-sm"></i>
                <span>Manajemen Tiket</span>
            </a>

            <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition text-slate-300 hover:bg-slate-800 hover:text-white">
                <i class="fa-solid fa-list-check text-sm"></i>
                <span>Semua Tiket</span>
            </a>

            <a href="{{ route('admin.analytics') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition bg-amber-400 text-[#0a2540]">
                <i class="fa-solid fa-chart-line text-sm"></i>
                <span>Analitik</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-700/60 shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar Admin</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 p-6 lg:p-8 space-y-6 overflow-y-auto">

        <!-- Header -->
        <div class="bg-gradient-to-r from-[#0a2540] to-[#16406c] rounded-2xl p-6 shadow-xl text-white">
            <h1 class="text-xl sm:text-2xl font-black tracking-tight">Ticketing System Kantor</h1>
            <p class="text-slate-300 text-xs mt-1">Ringkasan performa dan tren penanganan tiket helpdesk</p>
        </div>

        <!-- ===== FILTER BAR ===== -->
        <form method="GET" action="{{ route('admin.analytics') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" value="{{ $filters['tanggal_dari'] ?? '' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" value="{{ $filters['tanggal_sampai'] ?? '' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Status</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-amber-500">
                    @php $statusOpts = ['All' => 'Semua', 'Open' => 'Open', 'In Progress' => 'In Progress', 'Resolved' => 'Resolved', 'Closed' => 'Closed', 'Dibatalkan' => 'Dibatalkan Pelapor']; @endphp
                    @foreach($statusOpts as $val => $label)
                        <option value="{{ $val }}" {{ ($filters['status'] ?? 'All') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Prioritas</label>
                <select name="prioritas" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-xs focus:outline-none focus:border-amber-500">
                    @php $prioOpts = ['All' => 'Semua', 'Rendah' => 'Rendah', 'Sedang' => 'Sedang', 'Tinggi' => 'Tinggi']; @endphp
                    @foreach($prioOpts as $val => $label)
                        <option value="{{ $val }}" {{ ($filters['prioritas'] ?? 'All') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-2">Kategori</label>
                <div class="flex flex-wrap gap-x-4 gap-y-2">
                    @foreach($daftarKategori as $kat)
                    <label class="flex items-center gap-1.5 text-xs text-slate-600 font-medium">
                        <input type="checkbox" name="kategori[]" value="{{ $kat }}"
                            {{ in_array($kat, $filters['kategori'] ?? []) ? 'checked' : '' }}
                            class="rounded border-slate-300 text-amber-500 focus:ring-amber-400">
                        {{ $kat }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-[#0a2540] hover:bg-slate-800 text-white font-bold text-xs px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-filter mr-1"></i> Terapkan
                </button>
                <a href="{{ route('admin.analytics') }}" class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs px-4 py-2.5 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>

        <!-- ===== KPI CARDS ===== -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-[#0a2540] text-white rounded-2xl p-5 shadow-sm">
                <p class="text-3xl font-black">{{ number_format($totalTiket) }}</p>
                <p class="text-xs text-slate-300 italic mt-1">Total Tiket</p>
            </div>
            <div class="bg-[#0a2540] text-white rounded-2xl p-5 shadow-sm">
                <p class="text-3xl font-black">{{ $avgResolutionDays }}</p>
                <p class="text-xs text-slate-300 italic mt-1">Avg Resolution Time (hari)</p>
            </div>
            <div class="bg-[#0a2540] text-white rounded-2xl p-5 shadow-sm">
                <p class="text-3xl font-black">{{ $slaCompliance }}<span class="text-lg">%</span></p>
                <p class="text-xs text-slate-300 italic mt-1">SLA Compliance %</p>
            </div>
            <div class="bg-[#0a2540] text-white rounded-2xl p-5 shadow-sm">
                <p class="text-3xl font-black">{{ $persenOverdue }}<span class="text-lg">%</span></p>
                <p class="text-xs text-slate-300 italic mt-1">Persen Tiket Overdue</p>
            </div>
        </div>

        <!-- ===== ROW 1: Bar Kategori + Line Bulan ===== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-3">Total Tiket by Kategori</h3>
                <canvas id="chartKategori" height="220"></canvas>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-3">Total Tiket by Bulan</h3>
                <canvas id="chartBulan" height="220"></canvas>
            </div>
        </div>

        <!-- ===== ROW 2: Donut + Tabel ===== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <h3 class="text-sm font-bold text-slate-800 mb-3">Status Overdue vs On Time</h3>
                <canvas id="chartOverdue" height="220"></canvas>
            </div>

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 overflow-x-auto">
                <h3 class="text-sm font-bold text-slate-800 mb-3">Rekap Tiket per Bulan &amp; Hari</h3>
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-semibold border-b border-slate-100">
                            <th class="p-2.5">Bulan</th>
                            @foreach($days as $d)
                                <th class="p-2.5 text-center">{{ substr($d, 0, 3) }}</th>
                            @endforeach
                            <th class="p-2.5 text-center font-black">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        @forelse($tabelBulanHari as $row)
                        <tr class="hover:bg-slate-50/60">
                            <td class="p-2.5 font-semibold text-slate-700">{{ $row->bulan }}</td>
                            @foreach($days as $d)
                                <td class="p-2.5 text-center">{{ $row->$d }}</td>
                            @endforeach
                            <td class="p-2.5 text-center font-bold text-slate-800">{{ $row->total }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($days) + 2 }}" class="p-6 text-center text-slate-400">Tidak ada data pada rentang filter ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Passing data aman ke Javascript -->
    <div id="chartData"
        data-kategori-labels='@json($tiketByKategori->pluck("kategori"))'
        data-kategori-total='@json($tiketByKategori->pluck("total"))'
        data-bulan-labels='@json($tiketByBulan->pluck("bulan"))'
        data-bulan-total='@json($tiketByBulan->pluck("total"))'
        data-overdue="{{ $donutOverdue }}"
        data-ontime="{{ $donutOnTime }}"
        class="hidden"
    ></div>

    <script>
        const chartColors = ['#0a2540', '#2563eb', '#38bdf8', '#f59e0b', '#f97316', '#94a3b8', '#10b981'];

        const el = document.getElementById('chartData');
        const kategoriLabels = JSON.parse(el.dataset.kategoriLabels || '[]');
        const kategoriTotal  = JSON.parse(el.dataset.kategoriTotal || '[]');
        const bulanLabels    = JSON.parse(el.dataset.bulanLabels || '[]');
        const bulanTotal     = JSON.parse(el.dataset.bulanTotal || '[]');
        const donutOverdue   = Number(el.dataset.overdue || 0);
        const donutOnTime    = Number(el.dataset.ontime || 0);

        // ===== Chart: Kategori =====
        new Chart(document.getElementById('chartKategori'), {
            type: 'bar',
            data: {
                labels: kategoriLabels,
                datasets: [{
                    label: 'Total Tiket',
                    data: kategoriTotal,
                    backgroundColor: chartColors,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });

        // ===== Chart: Bulan =====
        new Chart(document.getElementById('chartBulan'), {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [{
                    label: 'Total Tiket',
                    data: bulanTotal,
                    fill: true,
                    backgroundColor: 'rgba(56, 189, 248, 0.25)',
                    borderColor: '#0ea5e9',
                    tension: 0.3,
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // ===== Chart: Donut Overdue =====
        new Chart(document.getElementById('chartOverdue'), {
            type: 'doughnut',
            data: {
                labels: ['Overdue', 'On Time'],
                datasets: [{
                    data: [donutOverdue, donutOnTime],
                    backgroundColor: ['#38bdf8', '#0a2540'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
            }
        });
    </script>
</body>
</html>