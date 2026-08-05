<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'admin_notif_new_ticket_read')) {
                $table->boolean('admin_notif_new_ticket_read')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'admin_notif_new_ticket_read')) {
                $table->dropColumn('admin_notif_new_ticket_read');
            }
        });
    }
};
