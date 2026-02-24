<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->string('nip')->unique();
            $table->string('pangkat_gol');
            $table->string('jabatan');
            $table->string('unit_kerja');
            $table->integer('sisa_cuti_tahunan')->default(12)->comment('Hanya informasi, tidak ada transaksi');
            $table->integer('sisa_cuti_tambahan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};