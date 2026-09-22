<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $columns = [
                'pj_notif_closed_read',
                'user_notif_resolved_read',
                'user_notif_assigned_read',
                'pj_notif_assigned_read',
                'user_notif_inprogress_read',
                'user_notif_admin_closed_read',
                'pj_notif_admin_closed_read',
                'admin_notif_user_closed_read',
                'admin_notif_new_ticket_read',
            ];

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
            $table->boolean('pj_notif_closed_read')->default(false)->after('closed_by');
            $table->boolean('user_notif_resolved_read')->default(false)->after('pj_notif_closed_read');
            $table->boolean('user_notif_assigned_read')->default(true)->after('user_notif_resolved_read');
            $table->boolean('pj_notif_assigned_read')->default(true)->after('user_notif_assigned_read');
            $table->boolean('user_notif_inprogress_read')->default(true)->after('pj_notif_assigned_read');
            $table->boolean('user_notif_admin_closed_read')->default(true)->after('user_notif_inprogress_read');
            $table->boolean('pj_notif_admin_closed_read')->default(true)->after('user_notif_admin_closed_read');
            $table->boolean('admin_notif_user_closed_read')->default(true)->after('pj_notif_admin_closed_read');
            $table->boolean('admin_notif_new_ticket_read')->default(false)->after('admin_notif_user_closed_read');
        });
    }
};
