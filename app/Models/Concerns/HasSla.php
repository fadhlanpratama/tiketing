<?php

namespace App\Models\Concerns;

use Carbon\Carbon;

/**
 * @property-read \Carbon\Carbon|null $waktu_mulai_dikerjakan
 * @property-read \Carbon\Carbon|null $tanggal_selesai
 * @property-read int|null            $sla_target_menit
 * @property-read int|null            $sla_lebih_menit
 * @property-read string|null         $sla_status
 * @property-read string|null         $status
 * @property-read string|null         $prioritas
 * @property-read \Carbon\Carbon|null $sla_deadline
 * @property-read int|null            $sla_lebih_menit_live
 * @property-read array|null          $sla_badge
 */

trait HasSla
{
    public static function slaTargetMenitMap(): array
    {
        return [
            'Tinggi' => 1 * 24 * 60,  // 1440 menit
            'Sedang' => 3 * 24 * 60,  // 4320 menit
            'Rendah' => 7 * 24 * 60,  // 10080 menit
        ];
    }

    public static function getSlaTargetMenitByPrioritas(?string $prioritas): ?int
    {
        return self::slaTargetMenitMap()[$prioritas] ?? null;
    }

    public function getSlaDeadlineAttribute(): ?Carbon
    {
        if (!$this->waktu_mulai_dikerjakan || !$this->sla_target_menit) {
            return null;
        }

        return $this->waktu_mulai_dikerjakan->copy()->addMinutes($this->sla_target_menit);
    }

    public function getSlaLebihMenitLiveAttribute(): ?int
    {
        $deadline = $this->sla_deadline;

        if (!$deadline) {
            return null;
        }

        $waktuBanding = in_array($this->status, ['Resolved', 'Closed']) && $this->tanggal_selesai
            ? $this->tanggal_selesai
            : Carbon::now();

        if ($waktuBanding->lessThanOrEqualTo($deadline)) {
            return 0;
        }

        return $deadline->diffInMinutes($waktuBanding);
    }

    public function getSlaBadgeAttribute(): ?array
    {
        if (!$this->waktu_mulai_dikerjakan) {
            return null;
        }

        $lebihMenit = $this->sla_lebih_menit_live;

        if ($lebihMenit === null) {
            return null;
        }

        return [
            'terlambat'     => $lebihMenit > 0,
            'lebih_menit'   => $lebihMenit,
            'label'         => $this->formatDurasiSla($lebihMenit),
            'sedang_proses' => $this->status === 'In Progress',
        ];
    }

    private function formatDurasiSla(int $menit): string
    {
        if ($menit <= 0) {
            return 'Tepat Waktu';
        }

        $hari = intdiv($menit, 1440);
        $sisaMenit = $menit % 1440;
        $jam = intdiv($sisaMenit, 60);
        $mnt = $sisaMenit % 60;

        $parts = [];
        if ($hari > 0) $parts[] = $hari . 'h';
        if ($jam > 0) $parts[] = $jam . 'j';
        if ($mnt > 0 || empty($parts)) $parts[] = $mnt . 'm';

        return '+' . implode(' ', $parts);
    }
}