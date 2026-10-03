<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Puskesmas',
                'username' => 'admin',
                'email' => 'admin@puskesmas.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'last_login_at' => now(),
            ],
            [
                'name' => 'Bidan Kesehatan',
                'username' => 'bidan',
                'email' => 'bidan@puskesmas.com',
                'role' => 'bidan',
                'password' => Hash::make('password'),
                'last_login_at' => now()->subHours(2),
            ],
            [
                'name' => 'Ahli Gizi',
                'username' => 'ahligizi',
                'email' => 'ahligizi@puskesmas.com',
                'role' => 'ahli_gizi',
                'password' => Hash::make('password'),
                'last_login_at' => now()->subDays(2),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['username' => $userData['username']], $userData);
        }
    }
}
