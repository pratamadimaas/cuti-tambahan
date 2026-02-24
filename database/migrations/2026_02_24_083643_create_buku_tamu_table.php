<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_tamu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satker');
            $table->string('nama_tamu');
            $table->foreignId('pegawai_kppn_id')->constrained('pegawai')->onDelete('cascade');
            $table->string('kantor');
            $table->string('foto_path');
            $table->text('keterangan')->nullable();
            $table->timestamp('waktu_kunjungan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_tamu');
    }
};