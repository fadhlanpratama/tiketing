<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class AdminAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:admin');
    }

    public function index(Request $request)
    {
        $query = Ticket::query();

        // ===== FILTER: Tanggal Masuk =====
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // ===== FILTER: Status =====
        if ($request->filled('status') && $request->status !== 'All') {
            if ($request->status === 'Dibatalkan') {
                $query->where('status', 'Closed')->where('closed_by', 'user');
            } else {
                $query->where('status', $request->status);
            }
        }

        // ===== FILTER: Prioritas =====
        if ($request->filled('prioritas') && $request->prioritas !== 'All') {
            $query->whereRaw('LOWER(prioritas) = ?', [strtolower($request->prioritas)]);
        }

        // ===== FILTER: Kategori =====
        if ($request->filled('kategori')) {
            $query->whereIn('kategori', (array) $request->kategori);
        }

        $base = $query;

        // ===== 1. KPI: Total Tiket =====
        $totalTiket = (clone $base)->count();

        // ===== 2. KPI: Rata-rata Waktu Penyelesaian (hari) =====
        // PERBAIKAN: Hanya menghitung tiket yang sudah selesai (Resolved/Closed) dan memilki tanggal_selesai
        $avgResolutionHours = (clone $base)
            ->whereIn('status', ['Resolved', 'Closed'])
            ->whereNotNull('tanggal_selesai')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, tanggal_selesai)) as avg_jam')
            ->value('avg_jam');

        $avgResolutionDays = $avgResolutionHours ? round($avgResolutionHours / 24, 1) : 0;

        // ===== 3 & 4. KPI: SLA Compliance % dan Persen Overdue =====
        // PERBAIKAN: Dihitung dari tiket yang sudah dievaluasi (tiket selesai ATAU tiket aktif yang sudah terlambat)
        $tiketEvaluasiSlaQuery = (clone $base)->where(function ($q) {
            $q->whereIn('status', ['Resolved', 'Closed'])
              ->orWhere('sla_status', 'Terlambat');
        });

        $totalTiketEvaluasiSla = $tiketEvaluasiSlaQuery->count();
        $slaTerlambat = (clone $base)->where('sla_status', 'Terlambat')->count();

        if ($totalTiketEvaluasiSla > 0) {
            $persenOverdue = round(($slaTerlambat / $totalTiketEvaluasiSla) * 100, 1);
            $slaCompliance = round((($totalTiketEvaluasiSla - $slaTerlambat) / $totalTiketEvaluasiSla) * 100, 1);
        } else {
            // Jika belum ada tiket selesai/terlambat pada kriteria filter
            $slaCompliance = 100;
            $persenOverdue = 0;
        }

        // ===== 5. Chart: Total Tiket by Kategori =====
        $tiketByKategori = (clone $base)
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        // ===== 6. Chart: Total Tiket by Bulan =====
        $tiketByBulan = (clone $base)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

       // ===== 7. Donut: Status Overdue vs On Time (Fallback jika filter Open/Dibatalkan) =====
        if ($totalTiketEvaluasiSla > 0) {
            $donutOverdue = $slaTerlambat;
            $donutOnTime  = max($totalTiketEvaluasiSla - $slaTerlambat, 0);
        } else {
            // Jika memfilter tiket Open yang belum overdue / Dibatalkan
            // Tampilkan seluruh total tiket filter saat ini sebagai 'On Time / Dalam Proses'
            $donutOverdue = 0;
            $donutOnTime  = $totalTiket; 
        }

        // ===== 8. Tabel Matrix: Bulan x Hari =====
        $daysMap = [
            0 => 'Monday',
            1 => 'Tuesday',
            2 => 'Wednesday',
            3 => 'Thursday',
            4 => 'Friday',
            5 => 'Saturday',
            6 => 'Sunday'
        ];

        $selectDays = collect($daysMap)->map(function ($name, $num) {
            return "SUM(CASE WHEN WEEKDAY(created_at) = {$num} THEN 1 ELSE 0 END) as `{$name}`";
        })->implode(', ');

        $tabelBulanHari = (clone $base)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, {$selectDays}, COUNT(*) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // ===== Data untuk dropdown/filter Kategori =====
        $daftarKategori = Ticket::select('kategori')->distinct()->pluck('kategori');

        return view('admin.dashboard', [
            'totalTiket'        => $totalTiket,
            'avgResolutionDays' => $avgResolutionDays,
            'slaCompliance'     => $slaCompliance,
            'persenOverdue'     => $persenOverdue,
            'tiketByKategori'   => $tiketByKategori,
            'tiketByBulan'      => $tiketByBulan,
            'donutOverdue'      => $donutOverdue,
            'donutOnTime'       => $donutOnTime,
            'tabelBulanHari'    => $tabelBulanHari,
            'days'              => array_values($daysMap),
            'daftarKategori'    => $daftarKategori,
            'filters'           => $request->only(['tanggal_dari', 'tanggal_sampai', 'status', 'prioritas', 'kategori']),
        ]);
    }
}