<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Kolom Keluhan & Aset
            $table->string('kategori', 100)->index();
            $table->string('sub_kategori', 150);
            $table->text('deskripsi_masalah');
            $table->string('nomor_bmn', 100)->nullable()->default(null)->comment('Nomor Barang Milik Negara');
            $table->string('attachment_foto', 255)->nullable()->default(null);
            
            // Pengelolaan & Validasi
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi'])->default('Rendah');
            $table->enum('status', ['Open', 'In Progress', 'Resolved', 'Closed'])->default('Open')->index();
            $table->string('penanggung_jawab', 150)->nullable()->default(null);
            
            // Penyelesaian & Feedback
            $table->timestamp('tanggal_selesai')->nullable()->default(null);
            $table->string('hasil_resolved_foto', 255)->nullable()->default(null)->comment('Foto bukti penyelesaian');
            $table->enum('survei_kepuasan', ['Puas', 'Cukup', 'Tidak Puas'])->nullable()->default(null);
            
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};