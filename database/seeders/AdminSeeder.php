<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        User::updateOrCreate(
            ['email' => 'admin@spkjurusan.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@spkjurusan.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '08123456789',
                'school' => '-',
                'email_verified_at' => now(),
            ]
        );

        // Create demo user
        User::updateOrCreate(
            ['email' => 'user@spkjurusan.com'],
            [
                'name' => 'Budi Santoso',
                'email' => 'user@spkjurusan.com',
                'password' => Hash::make('user1234'),
                'role' => 'user',
                'phone' => '08198765432',
                'school' => 'SMAN 1 Jakarta',
                'email_verified_at' => now(),
            ]
        );
    }
}
