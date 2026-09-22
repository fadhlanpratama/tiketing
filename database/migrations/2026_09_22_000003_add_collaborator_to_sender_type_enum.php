<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE ticket_messages MODIFY sender_type ENUM('user', 'pj', 'collaborator') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ticket_messages MODIFY sender_type ENUM('user', 'pj') NOT NULL");
    }
};
