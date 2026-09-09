<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tickets')
            ->whereNull('assigned_at')
            ->whereNotNull('waktu_mulai_dikerjakan')
            ->whereNotNull('pj_id')
            ->orderBy('id')
            ->chunkById(500, function ($tickets) {
                foreach ($tickets as $ticket) {
                    DB::table('tickets')
                        ->where('id', $ticket->id)
                        ->update(['assigned_at' => $ticket->waktu_mulai_dikerjakan]);

                    $assignedExists = DB::table('ticket_status_histories')
                        ->where('ticket_id', $ticket->id)
                        ->where('event_type', 'assigned')
                        ->exists();

                    if (!$assignedExists) {
                        DB::table('ticket_status_histories')->insert([
                            'ticket_id' => $ticket->id,
                            'event_type' => 'assigned',
                            'from_status' => 'Open',
                            'to_status' => 'Open',
                            'actor_id' => null,
                            'actor_role' => 'admin',
                            'occurred_at' => $ticket->waktu_mulai_dikerjakan,
                            'created_at' => $ticket->waktu_mulai_dikerjakan,
                            'updated_at' => $ticket->waktu_mulai_dikerjakan,
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        DB::table('ticket_status_histories')
            ->where('event_type', 'assigned')
            ->where('actor_id', null)
            ->where('actor_role', 'admin')
            ->delete();

        DB::table('tickets')
            ->whereColumn('assigned_at', 'waktu_mulai_dikerjakan')
            ->update(['assigned_at' => null]);
    }
};
