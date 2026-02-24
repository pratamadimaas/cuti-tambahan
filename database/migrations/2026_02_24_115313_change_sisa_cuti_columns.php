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
        Schema::table('pegawai', function (Blueprint $table) {
            $table->decimal('sisa_cuti_tahunan', 5, 1)->default(12)->change();
            $table->decimal('sisa_cuti_tambahan', 5, 1)->default(0)->change();
            $table->decimal('kuota_cuti_tahunan', 5, 1)->nullable()->change();
            $table->decimal('kuota_cuti_tambahan', 5, 1)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->integer('sisa_cuti_tahunan')->default(12)->change();
            $table->integer('sisa_cuti_tambahan')->default(0)->change();
            $table->integer('kuota_cuti_tahunan')->nullable()->change();
            $table->integer('kuota_cuti_tambahan')->nullable()->change();
        });
    }
    };
