<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE ticket_slas MODIFY COLUMN status ENUM('Berjalan', 'Tepat Waktu', 'Terlambat', 'Dibatalkan') NULL");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE ticket_slas MODIFY COLUMN status ENUM('Berjalan', 'Tepat Waktu', 'Terlambat') NULL");
    }
};
