<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Ticket;

class AdminAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:admin');
    }

    public function index(Request $request)
    {
        $filters = $request->only(['tanggal_dari', 'tanggal_sampai', 'status', 'prioritas', 'kategori']);
        
        $cacheKey = 'admin-dashboard:' . md5(json_encode($filters));

        // Cache hasil kalkulasi selama 60 detik
        $analytics = Cache::remember($cacheKey, now()->addSeconds(60), function () use ($request) {
            return $this->calculateAnalytics($request);
        });

        // Cache daftar kategori selama 10 menit
        $daftarKategori = Cache::remember('admin-dashboard:categories', now()->addMinutes(10), function () {
            return Ticket::distinct()->pluck('kategori');
        });

        return view('admin.dashboard', array_merge($analytics, [
            'daftarKategori' => $daftarKategori,
            'filters'        => $filters,
        ]));
    }

    private function calculateAnalytics(Request $request): array
    {
        $query = Ticket::query();

        // ===== FILTER: Tanggal Masuk =====
        if ($request->filled('tanggal_dari')) {
            $query->where('created_at', '>=', $request->date('tanggal_dari')->startOfDay());
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('created_at', '<=', $request->date('tanggal_sampai')->endOfDay());
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
            $query->where('prioritas', $request->prioritas);
        }

        // ===== FILTER: Kategori =====
        if ($request->filled('kategori')) {
            $query->whereIn('kategori', (array) $request->kategori);
        }

        $base = $query;

        // ===== OPTIMASI KPI =====
        $kpi = (clone $base)
            ->selectRaw("
                COUNT(*) as total_tiket,
                AVG(CASE WHEN status IN ('Resolved', 'Closed') AND tanggal_selesai IS NOT NULL 
                        THEN TIMESTAMPDIFF(HOUR, created_at, tanggal_selesai) 
                        ELSE NULL END) as avg_jam,
                COUNT(CASE WHEN status IN ('Resolved', 'Closed', 'In Progress') 
                        THEN 1 ELSE NULL END) as total_evaluasi_sla,
                COUNT(CASE 
                        WHEN status IN ('Resolved', 'Closed') AND sla_status = 'Terlambat' THEN 1
                        WHEN status = 'In Progress' 
                                AND waktu_mulai_dikerjakan IS NOT NULL
                                AND DATE_ADD(waktu_mulai_dikerjakan, INTERVAL sla_target_menit MINUTE) < NOW() THEN 1
                        ELSE NULL 
                    END) as sla_terlambat
            ")
            ->first();

        $totalTiket = $kpi->total_tiket ?? 0;
        $avgResolutionHours = $kpi->avg_jam;
        $avgResolutionDays = $avgResolutionHours ? round($avgResolutionHours / 24, 1) : 0;

        $totalTiketEvaluasiSla = $kpi->total_evaluasi_sla ?? 0;
        $slaTerlambat = $kpi->sla_terlambat ?? 0;

        if ($totalTiketEvaluasiSla > 0) {
            $persenOverdue = round(($slaTerlambat / $totalTiketEvaluasiSla) * 100, 1);
            $slaCompliance = round((($totalTiketEvaluasiSla - $slaTerlambat) / $totalTiketEvaluasiSla) * 100, 1);
            $donutOverdue  = $slaTerlambat;
            $donutOnTime   = max($totalTiketEvaluasiSla - $slaTerlambat, 0);
        } else {
            $slaCompliance = 100;
            $persenOverdue = 0;
            $donutOverdue  = 0;
            $donutOnTime   = $totalTiket;
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

        // ===== 7. Tabel Matrix: Bulan x Hari =====
        $daysMap = [
            0 => 'Monday', 1 => 'Tuesday', 2 => 'Wednesday',
            3 => 'Thursday', 4 => 'Friday', 5 => 'Saturday', 6 => 'Sunday'
        ];

        $selectDays = collect($daysMap)->map(function ($name, $num) {
            return "SUM(CASE WHEN WEEKDAY(created_at) = {$num} THEN 1 ELSE 0 END) as `{$name}`";
        })->implode(', ');

        $tabelBulanHari = (clone $base)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, {$selectDays}, COUNT(*) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return [
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
        ];
    }
}