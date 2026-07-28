<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'user_notif_assigned_read')) {
                $table->boolean('user_notif_assigned_read')->default(true);
            }
            if (!Schema::hasColumn('tickets', 'pj_notif_assigned_read')) {
                $table->boolean('pj_notif_assigned_read')->default(true);
            }
            if (!Schema::hasColumn('tickets', 'user_notif_inprogress_read')) {
                $table->boolean('user_notif_inprogress_read')->default(true);
            }
            if (!Schema::hasColumn('tickets', 'user_notif_admin_closed_read')) {
                $table->boolean('user_notif_admin_closed_read')->default(true);
            }
            if (!Schema::hasColumn('tickets', 'pj_notif_admin_closed_read')) {
                $table->boolean('pj_notif_admin_closed_read')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $columns = [
                'user_notif_assigned_read',
                'pj_notif_assigned_read',
                'user_notif_inprogress_read',
                'user_notif_admin_closed_read',
                'pj_notif_admin_closed_read',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('tickets', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};