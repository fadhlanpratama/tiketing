<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Models\Users;

class BackfillPjId extends Command
{
    protected $signature = 'backfill:pj_id {--run : Perform write operations} {--log= : Log file path (relative to storage/logs)}';
    protected $description = 'Backfill tickets.pj_id from penanggung_jawab when match to exactly one user (case-insensitive).';

    public function handle()
    {
        $doRun = (bool) $this->option('run');
        $logFile = $this->option('log') ? storage_path('logs/' . $this->option('log')) : storage_path('logs/pj_backfill.log');

        $this->info('Scanning tickets where pj_id IS NULL and penanggung_jawab is present...');

        $tickets = Ticket::whereNull('pj_id')
            ->whereNotNull('penanggung_jawab')
            ->where('penanggung_jawab', '!=', '')
            ->get();

        $total = $tickets->count();
        $assigned = 0;
        $ambiguous = 0;
        $missing = 0;

        foreach ($tickets as $ticket) {
            $name = trim($ticket->penanggung_jawab);
            if ($name === '') continue;

            $matches = Users::whereRaw('LOWER(TRIM(nama_lengkap)) = ?', [strtolower($name)])->get();

            if ($matches->count() === 1) {
                $user = $matches->first();
                if ($doRun) {
                    $ticket->pj_id = $user->id;
                    $ticket->timestamps = false;
                    $ticket->save();
                    $this->line("Assigned ticket #{$ticket->id} -> user {$user->id} ({$user->nama_lengkap})");
                } else {
                    $this->line("[DRY] Would assign ticket #{$ticket->id} -> user {$user->id} ({$user->nama_lengkap})");
                }
                $assigned++;

            } elseif ($matches->count() > 1) {
                $ambiguous++;
                $candidates = $matches->map(fn($u) => ['id' => $u->id, 'nama' => $u->nama_lengkap])->toArray();
                $entry = ['ticket_id' => $ticket->id, 'penanggung_jawab' => $name, 'candidates' => $candidates, 'reason' => 'ambiguous'];
                file_put_contents($logFile, json_encode($entry) . PHP_EOL, FILE_APPEND | LOCK_EX);
                $this->warn("Ambiguous for ticket #{$ticket->id}: {$name} -> " . count($candidates) . " candidates (logged)");

            } else {
                $missing++;
                $entry = ['ticket_id' => $ticket->id, 'penanggung_jawab' => $name, 'candidates' => [], 'reason' => 'no_match'];
                file_put_contents($logFile, json_encode($entry) . PHP_EOL, FILE_APPEND | LOCK_EX);
                $this->warn("No user match for ticket #{$ticket->id}: {$name} (logged)");
            }
        }

        $this->info('Done. Summary:');
        $this->info("Total scanned: {$total}");
        $this->info("Unambiguous matches: {$assigned}");
        $this->info("Ambiguous entries: {$ambiguous} (see {$logFile})");
        $this->info("No-match entries: {$missing} (see {$logFile})");

        if (!$doRun) {
            $this->comment('This was a dry-run. Re-run with `php artisan backfill:pj_id --run` to apply changes.');
        }

        return 0;
    }
}
