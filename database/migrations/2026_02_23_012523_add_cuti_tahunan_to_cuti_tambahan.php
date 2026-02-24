<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuti_tambahan', function (Blueprint $table) {
            $table->integer('cuti_tahunan_jumlah')->default(0)->after('cuti_tambahan_jumlah');
        });
    }

    public function down(): void
    {
        Schema::table('cuti_tambahan', function (Blueprint $table) {
            $table->dropColumn('cuti_tahunan_jumlah');
        });
    }
};