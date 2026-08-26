<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->index(['created_at', 'status', 'prioritas'], 'idx_tickets_analytics_filter');
            $table->index(['created_at', 'kategori'], 'idx_tickets_created_kategori');
            $table->index(['status', 'tanggal_selesai'], 'idx_tickets_resolution_time');
            $table->index('sla_status', 'idx_tickets_sla');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('idx_tickets_analytics_filter');
            $table->dropIndex('idx_tickets_created_kategori');
            $table->dropIndex('idx_tickets_resolution_time');
            $table->dropIndex('idx_tickets_sla');
        });
    }
};