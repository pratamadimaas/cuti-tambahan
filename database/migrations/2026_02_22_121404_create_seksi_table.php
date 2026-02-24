<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seksi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_seksi');
            $table->string('nama_kepala_seksi');
            $table->string('nip_kepala_seksi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seksi');
    }
};