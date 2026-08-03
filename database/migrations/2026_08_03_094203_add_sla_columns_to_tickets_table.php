<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->timestamp('waktu_mulai_dikerjakan')->nullable()->after('penanggung_jawab')
                ->comment('Waktu PJ mulai mengerjakan tiket, dasar hitung SLA');

            $table->unsignedInteger('sla_target_menit')->nullable()->after('waktu_mulai_dikerjakan')
                ->comment('Target waktu penyelesaian (menit) sesuai prioritas saat tiket mulai dikerjakan');

            $table->unsignedInteger('sla_lebih_menit')->nullable()->after('sla_target_menit')
                ->comment('Kelebihan waktu dari target SLA dalam menit, 0 jika tepat waktu. NULL jika belum selesai.');

            $table->enum('sla_status', ['Berjalan', 'Tepat Waktu', 'Terlambat'])
                ->default('Berjalan')
                ->after('sla_lebih_menit')
                ->comment('Status SLA final tiket, dipakai untuk laporan performa PJ');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['waktu_mulai_dikerjakan', 'sla_target_menit', 'sla_lebih_menit', 'sla_status']);
        });
    }
};