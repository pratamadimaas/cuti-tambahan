<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuti_tambahan', function (Blueprint $table) {
            $table->json('tanggal_cuti_tambahan')->nullable()->after('tanggal_cuti');
            $table->json('tanggal_cuti_tahunan')->nullable()->after('tanggal_cuti_tambahan');
        });
    }

    public function down(): void
    {
        Schema::table('cuti_tambahan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_cuti_tambahan', 'tanggal_cuti_tahunan']);
        });
    }
};