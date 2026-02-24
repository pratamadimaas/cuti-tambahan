<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KepalaKantor;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('super'),
            'role' => 'admin',
        ]);

        // Create Default Kepala Kantor
        KepalaKantor::create([
            'nama' => 'Setyawan',
            'nip' => '196501011990031001',
            'pangkat_gol' => 'Pembina Utama Muda (IV/c)',
        ]);
    }
}