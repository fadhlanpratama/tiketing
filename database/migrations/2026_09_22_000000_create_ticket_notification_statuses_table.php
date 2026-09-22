<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_notification_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('role');
            $table->string('key');
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->unique(['ticket_id', 'user_id', 'role', 'key'], 'ticket_notification_status_unique');
            $table->index(['role', 'key']);
        });

        $this->backfillTicketNotificationStatuses();
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_notification_statuses');
    }

    protected function backfillTicketNotificationStatuses(): void
    {
        $tickets = DB::table('tickets')->get([
            'id',
            'user_id',
            'pj_id',
            'admin_notif_new_ticket_read',
            'admin_notif_user_closed_read',
            'pj_notif_closed_read',
            'pj_notif_assigned_read',
            'pj_notif_admin_closed_read',
            'user_notif_resolved_read',
            'user_notif_assigned_read',
            'user_notif_inprogress_read',
            'user_notif_admin_closed_read',
        ]);

        foreach ($tickets as $ticket) {
            $rows = [];

            if (!is_null($ticket->user_id)) {
                $rows[] = ['ticket_id' => $ticket->id, 'user_id' => $ticket->user_id, 'role' => 'user', 'key' => 'resolved', 'read' => (bool) ($ticket->user_notif_resolved_read ?? false)];
                $rows[] = ['ticket_id' => $ticket->id, 'user_id' => $ticket->user_id, 'role' => 'user', 'key' => 'assigned', 'read' => (bool) ($ticket->user_notif_assigned_read ?? true)];
                $rows[] = ['ticket_id' => $ticket->id, 'user_id' => $ticket->user_id, 'role' => 'user', 'key' => 'in_progress', 'read' => (bool) ($ticket->user_notif_inprogress_read ?? true)];
                $rows[] = ['ticket_id' => $ticket->id, 'user_id' => $ticket->user_id, 'role' => 'user', 'key' => 'admin_closed', 'read' => (bool) ($ticket->user_notif_admin_closed_read ?? true)];
            }

            if (!is_null($ticket->pj_id)) {
                $rows[] = ['ticket_id' => $ticket->id, 'user_id' => $ticket->pj_id, 'role' => 'pj', 'key' => 'user_closed', 'read' => (bool) ($ticket->pj_notif_closed_read ?? false)];
                $rows[] = ['ticket_id' => $ticket->id, 'user_id' => $ticket->pj_id, 'role' => 'pj', 'key' => 'assigned', 'read' => (bool) ($ticket->pj_notif_assigned_read ?? true)];
                $rows[] = ['ticket_id' => $ticket->id, 'user_id' => $ticket->pj_id, 'role' => 'pj', 'key' => 'admin_closed', 'read' => (bool) ($ticket->pj_notif_admin_closed_read ?? true)];
            }

            $rows[] = ['ticket_id' => $ticket->id, 'user_id' => null, 'role' => 'admin', 'key' => 'new_ticket', 'read' => (bool) ($ticket->admin_notif_new_ticket_read ?? false)];
            $rows[] = ['ticket_id' => $ticket->id, 'user_id' => null, 'role' => 'admin', 'key' => 'user_closed', 'read' => (bool) ($ticket->admin_notif_user_closed_read ?? true)];

            foreach ($rows as $row) {
                DB::table('ticket_notification_statuses')->updateOrInsert(
                    [
                        'ticket_id' => $row['ticket_id'],
                        'user_id' => $row['user_id'],
                        'role' => $row['role'],
                        'key' => $row['key'],
                    ],
                    [
                        'read' => $row['read'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
};
