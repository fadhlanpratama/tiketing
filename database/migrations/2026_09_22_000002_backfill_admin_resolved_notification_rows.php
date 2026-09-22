<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $ticketIds = DB::table('tickets')
            ->where('status', 'Resolved')
            ->pluck('id');

        foreach ($ticketIds as $ticketId) {
            $exists = DB::table('ticket_notification_statuses')
                ->where('ticket_id', $ticketId)
                ->whereNull('user_id')
                ->where('role', 'admin')
                ->where('key', 'resolved')
                ->exists();

            if (! $exists) {
                DB::table('ticket_notification_statuses')->insert([
                    'ticket_id' => $ticketId,
                    'user_id' => null,
                    'role' => 'admin',
                    'key' => 'resolved',
                    'read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('ticket_notification_statuses')
            ->whereNull('user_id')
            ->where('role', 'admin')
            ->where('key', 'resolved')
            ->delete();
    }
};
