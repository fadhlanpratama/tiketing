<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true)->unsigned(false)->comment(''); // Kunci Utama Auto Increment
            $table->string('nama_lengkap', 150)->comment('Nama lengkap');
            $table->string('divisi', 150)->nullable()->default(null);
            $table->string('email', 100)->unique()->nullable()->default(null); // Ikon kunci merah di gambar menandakan Unique/Index
            $table->string('no_telp', 50)->nullable()->default(null);
            $table->string('password', 255)->comment('Password yang sudah di-hash');
            $table->enum('role', ['user', 'admin'])->default('user')->comment('Hak akses user');
            
            // Kolom created_at & updated_at otomatis mengikuti CURRENT_TIMESTAMP sesuai gambar
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};