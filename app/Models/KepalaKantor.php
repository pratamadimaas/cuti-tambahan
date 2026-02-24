<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaKantor extends Model
{
    protected $table = 'kepala_kantor';

    protected $fillable = [
        'nama',
        'nip',
        'pangkat_gol',
    ];

    public static function getActive()
    {
        return self::latest()->first();
    }
}