<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hanya memasukkan akun kamu saja yang strukturnya pas dengan tabel baru kita
        User::create([
            'nim' => '230104040083',
            'name' => 'Nurmiyaty',
            'email' => 'mnurmiyaty2@gmail.com',
            'password' => Hash::make('password123'), // Ini password login kamu nanti
            'role' => 'pembeli',
            'lokasi_kampus' => 'Banjarbaru',
        ]);
    }
}