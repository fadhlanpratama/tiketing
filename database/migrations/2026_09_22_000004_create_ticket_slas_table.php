<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_slas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->unique()->constrained('tickets')->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->unsignedInteger('target_minutes')->nullable();
            $table->unsignedInteger('elapsed_minutes')->nullable();
            $table->enum('status', ['Berjalan', 'Tepat Waktu', 'Terlambat'])->nullable();
            $table->timestamps();
        });

        $tickets = DB::table('tickets')->get([
            'id',
            'waktu_mulai_dikerjakan',
            'sla_target_menit',
            'sla_lebih_menit',
            'sla_status',
        ]);

        foreach ($tickets as $ticket) {
            $startedAt = $ticket->waktu_mulai_dikerjakan;
            $targetMinutes = $ticket->sla_target_menit;

            if ($startedAt === null && $targetMinutes === null && $ticket->sla_lebih_menit === null && $ticket->sla_status === null) {
                continue;
            }

            DB::table('ticket_slas')->updateOrInsert(
                ['ticket_id' => $ticket->id],
                [
                    'started_at' => $startedAt,
                    'target_minutes' => $targetMinutes,
                    'elapsed_minutes' => $ticket->sla_lebih_menit,
                    'status' => $ticket->sla_status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        Schema::table('tickets', function (Blueprint $table) {
            $columns = ['waktu_mulai_dikerjakan', 'sla_target_menit', 'sla_lebih_menit', 'sla_status'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('tickets', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'waktu_mulai_dikerjakan')) {
                $table->timestamp('waktu_mulai_dikerjakan')->nullable()->after('penanggung_jawab');
            }
            if (!Schema::hasColumn('tickets', 'sla_target_menit')) {
                $table->unsignedInteger('sla_target_menit')->nullable()->after('waktu_mulai_dikerjakan');
            }
            if (!Schema::hasColumn('tickets', 'sla_lebih_menit')) {
                $table->unsignedInteger('sla_lebih_menit')->nullable()->after('sla_target_menit');
            }
            if (!Schema::hasColumn('tickets', 'sla_status')) {
                $table->enum('sla_status', ['Berjalan', 'Tepat Waktu', 'Terlambat'])->nullable()->after('sla_lebih_menit');
            }
        });

        $ticketSlas = DB::table('ticket_slas')->get();

        foreach ($ticketSlas as $sla) {
            DB::table('tickets')
                ->where('id', $sla->ticket_id)
                ->update([
                    'waktu_mulai_dikerjakan' => $sla->started_at,
                    'sla_target_menit' => $sla->target_minutes,
                    'sla_lebih_menit' => $sla->elapsed_minutes,
                    'sla_status' => $sla->status,
                ]);
        }

        Schema::dropIfExists('ticket_slas');
    }
};
