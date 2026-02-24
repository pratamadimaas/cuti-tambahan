<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->integer('kuota_cuti_tahunan')->default(12)->after('seksi_id');
            $table->integer('kuota_cuti_tambahan')->default(12)->after('kuota_cuti_tahunan');
        });
    }

    public function down()
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn(['kuota_cuti_tahunan', 'kuota_cuti_tambahan']);
        });
    }
};