<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun admin default untuk mengelola portofolio
        User::create([
            'name' => 'Admin Portfolio',
            'email' => 'admin@portfolio.local',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Seed data profil dan skill awal
        $this->call([
            PortfolioSeeder::class,
        ]);
    }
}
