<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->boolean('read_by_pj')->default(false)->after('foto');
            $table->boolean('read_by_user')->default(false)->after('read_by_pj');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->boolean('pj_notif_closed_read')->default(false)->after('closed_by');
            $table->boolean('user_notif_resolved_read')->default(false)->after('pj_notif_closed_read');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropColumn(['read_by_pj', 'read_by_user']);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['pj_notif_closed_read', 'user_notif_resolved_read']);
        });
    }
};