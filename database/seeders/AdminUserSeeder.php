<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@kurban.local'],
            [
                'name'     => 'Administrator',
                'password' => bcrypt('admin123'),
                'role'     => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@kurban.local'],
            [
                'name'     => 'Petugas',
                'password' => bcrypt('user123'),
                'role'     => 'user',
            ]
        );
    }
}
