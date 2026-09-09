<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Ticket;

class AdminAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('cek.login:admin');
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'prioritas', 'kategori']);
        $bulanParam = $request->input('bulan');

        try {
            $bulanAktif = $bulanParam
                ? Carbon::createFromFormat('Y-m', $bulanParam)->startOfMonth()
                : now()->startOfMonth();
        } catch (\Exception $e) {
            $bulanAktif = now()->startOfMonth();
        }

        $isBulanBerjalan = $bulanAktif->isSameMonth(now());
        $periodStart = $isBulanBerjalan
            ? now()->startOfDay()
            : $bulanAktif->copy()->startOfMonth();
        $periodEnd   = $bulanAktif->copy()->endOfMonth();
        $periodLabel = $bulanAktif->translatedFormat('F Y');
        $defaultPeriodStart = $periodStart->copy();
        $defaultPeriodEnd   = $periodEnd->copy();
        $tanggalDariInput   = $request->input('tanggal_dari');
        $tanggalSampaiInput = $request->input('tanggal_sampai');
        $adaFilterTanggal   = false;

        if ($request->filled('tanggal_dari') || $request->filled('tanggal_sampai')) {
            try {
                $customDari = $request->filled('tanggal_dari')
                    ? Carbon::parse($tanggalDariInput)->startOfDay()
                    : $defaultPeriodStart->copy();

                $customSampai = $request->filled('tanggal_sampai')
                    ? Carbon::parse($tanggalSampaiInput)->endOfDay()
                    : $defaultPeriodEnd->copy();

                if ($customDari->greaterThan($customSampai)) {
                    $tmp = $customDari;
                    $customDari = $customSampai->copy()->startOfDay();
                    $customSampai = $tmp->copy()->endOfDay();
                }

                $samaDenganDefault = $customDari->toDateString() === $defaultPeriodStart->toDateString()
                    && $customSampai->toDateString() === $defaultPeriodEnd->toDateString();

                if (!$samaDenganDefault) {
                    $periodStart = $customDari;
                    $periodEnd   = $customSampai;
                    $periodLabel = $periodStart->isSameDay($periodEnd)
                        ? $periodStart->translatedFormat('d M Y')
                        : $periodStart->translatedFormat('d M Y') . ' – ' . $periodEnd->translatedFormat('d M Y');
                    $adaFilterTanggal = true;
                }
            } catch (\Exception $e) {
            }
        }

        $isPeriodeBerjalan = $periodEnd->greaterThanOrEqualTo(now()->startOfDay());
        $cacheDuration = $isPeriodeBerjalan ? now()->addSeconds(60) : now()->addDay();

        $cacheKeyParts = array_merge($filters, [
            'periode' => $periodStart->toDateString() . '_' . $periodEnd->toDateString(),
        ]);
        $cacheKey = 'admin-dashboard:' . md5(json_encode($cacheKeyParts));

        $analytics = Cache::remember($cacheKey, $cacheDuration, function () use ($request, $periodStart, $periodEnd) {
            return $this->calculateAnalytics($request, $periodStart, $periodEnd);
        });

        // Cache daftar kategori selama 10 menit
        $daftarKategori = Cache::remember('admin-dashboard:categories', now()->addMinutes(10), function () {
            return Ticket::distinct()->pluck('kategori');
        });

        $bulanSebelumnya = $bulanAktif->copy()->subMonth()->format('Y-m');
        $bulanBerikutnyaObj = $bulanAktif->copy()->addMonth();
        $bisaMaju = $bulanBerikutnyaObj->lte(now()->startOfMonth());
        $isDefaultView = $isBulanBerjalan && !$adaFilterTanggal;
        $tanggalRangeLabel = $periodStart->isSameDay($periodEnd)
            ? $periodStart->translatedFormat('d M Y')
            : $periodStart->translatedFormat('d M Y') . ' – ' . $periodEnd->translatedFormat('d M Y');

        return view('admin.dashboard', array_merge($analytics, [
            'daftarKategori'    => $daftarKategori,
            'filters'           => $filters,
            'periodLabel'       => $periodLabel,
            'isDefaultView'     => $isDefaultView,
            'bulanSebelumnya'   => $bulanSebelumnya,
            'bulanBerikutnya'   => $bulanBerikutnyaObj->format('Y-m'),
            'bisaMaju'          => $bisaMaju,
            'adaFilterTanggal'  => $adaFilterTanggal,
            'tanggalRangeLabel' => $tanggalRangeLabel,
            'tanggalDariInput'  => $request->filled('tanggal_dari') ? $tanggalDariInput : $periodStart->toDateString(),
            'tanggalSampaiInput' => $request->filled('tanggal_sampai') ? $tanggalSampaiInput : $periodEnd->toDateString(),
            'batasMin'          => $bulanAktif->copy()->startOfMonth()->toDateString(),
            'batasMax'          => $bulanAktif->copy()->endOfMonth()->toDateString(),
        ]));
    }

    private function calculateAnalytics(Request $request, Carbon $periodStart, Carbon $periodEnd): array
    {
        $query = Ticket::query()
            ->where('created_at', '>=', $periodStart)
            ->where('created_at', '<=', $periodEnd);

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
        $historyEvent = function (string $eventType) {
            return DB::table('ticket_status_histories')
                ->select('ticket_id', DB::raw('MIN(occurred_at) as occurred_at'))
                ->where('event_type', $eventType)
                ->groupBy('ticket_id');
        };

        $kpi = (clone $base)
            ->leftJoinSub($historyEvent('created'), 'history_created', 'history_created.ticket_id', '=', 'tickets.id')
            ->leftJoinSub($historyEvent('assigned'), 'history_assigned', 'history_assigned.ticket_id', '=', 'tickets.id')
            ->leftJoinSub($historyEvent('started'), 'history_started', 'history_started.ticket_id', '=', 'tickets.id')
            ->leftJoinSub($historyEvent('resolved'), 'history_resolved', 'history_resolved.ticket_id', '=', 'tickets.id')
            ->leftJoinSub($historyEvent('closed'), 'history_closed', 'history_closed.ticket_id', '=', 'tickets.id')
            ->selectRaw("
                COUNT(*) as total_tiket,
                AVG(CASE WHEN (status = 'Resolved' OR (status = 'Closed' AND (closed_by = 'admin' OR closed_by IS NULL)))
                        AND history_created.occurred_at IS NOT NULL
                        AND (history_resolved.occurred_at IS NOT NULL OR history_closed.occurred_at IS NOT NULL)
                    THEN TIMESTAMPDIFF(MINUTE, history_created.occurred_at,
                        COALESCE(history_closed.occurred_at, history_resolved.occurred_at))
                    ELSE NULL END) as avg_total_menit,
                AVG(CASE WHEN history_created.occurred_at IS NOT NULL AND history_assigned.occurred_at IS NOT NULL
                    THEN TIMESTAMPDIFF(MINUTE, history_created.occurred_at, history_assigned.occurred_at)
                    ELSE NULL END) as avg_antrian_menit,
                AVG(CASE WHEN history_assigned.occurred_at IS NOT NULL AND history_started.occurred_at IS NOT NULL
                    THEN TIMESTAMPDIFF(MINUTE, history_assigned.occurred_at, history_started.occurred_at)
                    ELSE NULL END) as avg_tunggu_pj_menit,
                AVG(CASE WHEN history_started.occurred_at IS NOT NULL AND history_resolved.occurred_at IS NOT NULL
                    THEN TIMESTAMPDIFF(MINUTE, history_started.occurred_at, history_resolved.occurred_at)
                    ELSE NULL END) as avg_sla_menit,
                AVG(CASE WHEN history_resolved.occurred_at IS NOT NULL AND history_closed.occurred_at IS NOT NULL
                    THEN TIMESTAMPDIFF(MINUTE, history_resolved.occurred_at, history_closed.occurred_at)
                    ELSE NULL END) as avg_tunggu_closed_menit,
                COUNT(CASE WHEN status IN ('Resolved', 'In Progress')
                    OR (status = 'Closed' AND (closed_by = 'admin' OR closed_by IS NULL))
                        THEN 1 ELSE NULL END) as total_evaluasi_sla,
                COUNT(CASE 
                    WHEN (status = 'Resolved' OR (status = 'Closed' AND (closed_by = 'admin' OR closed_by IS NULL)))
                        AND history_started.occurred_at IS NOT NULL
                        AND history_resolved.occurred_at IS NOT NULL
                        AND TIMESTAMPDIFF(MINUTE, history_started.occurred_at, history_resolved.occurred_at) > sla_target_menit THEN 1
                        WHEN status = 'In Progress' 
                        AND history_started.occurred_at IS NOT NULL
                            AND DATE_ADD(history_started.occurred_at, INTERVAL sla_target_menit MINUTE) < NOW() THEN 1
                        ELSE NULL 
                    END) as sla_terlambat
            ")
            ->first();

        $totalTiket = $kpi->total_tiket ?? 0;
        $avgTotalMinutes = $kpi->avg_total_menit;
        $avgResolutionDays = $avgTotalMinutes ? round($avgTotalMinutes / 1440, 1) : 0;

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
            'avgAntrianHours'   => $this->formatAverageHours($kpi->avg_antrian_menit),
            'avgTungguPjHours'  => $this->formatAverageHours($kpi->avg_tunggu_pj_menit),
            'avgSlaHours'       => $this->formatAverageHours($kpi->avg_sla_menit),
            'avgTungguClosedHours' => $this->formatAverageHours($kpi->avg_tunggu_closed_menit),
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

    private function formatAverageHours(?float $minutes): string
    {
        return $minutes === null ? '-' : number_format($minutes / 60, 1, ',', '.') . ' jam';
    }
}