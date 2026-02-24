<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuti_tambahan', function (Blueprint $table) {
            // Ubah jumlah jadi decimal agar bisa 0.5
            $table->decimal('cuti_tahunan_jumlah', 4, 1)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('cuti_tambahan', function (Blueprint $table) {
            $table->integer('cuti_tahunan_jumlah')->default(0)->change();
        });
    }
};