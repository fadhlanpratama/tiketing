<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->string('event_type', 30);
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30)->nullable();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor_role', 30)->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();

            $table->index(['ticket_id', 'event_type', 'occurred_at']);
        });

        DB::table('tickets')->orderBy('id')->chunkById(500, function ($tickets) {
            foreach ($tickets as $ticket) {
                $events = [[
                    'ticket_id' => $ticket->id,
                    'event_type' => 'created',
                    'from_status' => null,
                    'to_status' => 'Open',
                    'actor_id' => $ticket->user_id,
                    'actor_role' => 'user',
                    'occurred_at' => $ticket->created_at,
                ]];

                if ($ticket->assigned_at) {
                    $events[] = [
                        'ticket_id' => $ticket->id,
                        'event_type' => 'assigned',
                        'from_status' => 'Open',
                        'to_status' => 'Open',
                        'actor_id' => null,
                        'actor_role' => 'admin',
                        'occurred_at' => $ticket->assigned_at,
                    ];
                }

                if ($ticket->waktu_mulai_dikerjakan) {
                    $events[] = [
                        'ticket_id' => $ticket->id,
                        'event_type' => 'started',
                        'from_status' => 'Open',
                        'to_status' => 'In Progress',
                        'actor_id' => $ticket->pj_id,
                        'actor_role' => 'pj',
                        'occurred_at' => $ticket->waktu_mulai_dikerjakan,
                    ];
                }

                if ($ticket->tanggal_selesai
                    && in_array($ticket->status, ['Resolved', 'Closed'], true)
                    && $ticket->closed_by !== 'user') {
                    $events[] = [
                        'ticket_id' => $ticket->id,
                        'event_type' => 'resolved',
                        'from_status' => 'In Progress',
                        'to_status' => 'Resolved',
                        'actor_id' => $ticket->pj_id,
                        'actor_role' => 'pj',
                        'occurred_at' => $ticket->tanggal_selesai,
                    ];
                }

                if ($ticket->status === 'Closed') {
                    $events[] = [
                        'ticket_id' => $ticket->id,
                        'event_type' => $ticket->closed_by === 'user' ? 'cancelled' : 'closed',
                        'from_status' => $ticket->closed_by === 'user' ? $ticket->status : 'Resolved',
                        'to_status' => 'Closed',
                        'actor_id' => $ticket->closed_by === 'user' ? $ticket->user_id : null,
                        'actor_role' => $ticket->closed_by === 'user' ? 'user' : 'admin',
                        'occurred_at' => $ticket->closed_at ?: $ticket->updated_at,
                    ];
                }

                foreach ($events as $event) {
                    DB::table('ticket_status_histories')->insert(array_merge($event, [
                        'created_at' => $event['occurred_at'],
                        'updated_at' => $event['occurred_at'],
                    ]));
                }
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_status_histories');
    }
};