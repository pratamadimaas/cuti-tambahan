<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuti_tambahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('nomor_nota_dinas')->nullable();
            $table->date('tanggal_nota_dinas');
            
            $table->integer('cuti_tambahan_jumlah');
            $table->date('cuti_tambahan_mulai');
            $table->date('cuti_tambahan_selesai');
            
            $table->text('alasan_cuti');
            $table->text('alamat_cuti');
            
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuti_tambahan');
    }
};