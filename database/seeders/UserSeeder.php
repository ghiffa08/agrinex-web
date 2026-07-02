<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@iot-irrigation.local',
                'password_hash' => bcrypt('admin123'),
                'full_name' => 'System Administrator',
                'role' => 'admin',
                'phone_number' => '081234567890',
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'username' => 'operator',
                'email' => 'operator@iot-irrigation.local',
                'password_hash' => bcrypt('operator123'),
                'full_name' => 'System Operator',
                'role' => 'operator',
                'phone_number' => '081234567891',
                'is_active' => true,
                'created_at' => now(),
            ],
            [
                'username' => 'viewer',
                'email' => 'viewer@iot-irrigation.local',
                'password_hash' => bcrypt('viewer123'),
                'full_name' => 'Guest Viewer',
                'role' => 'viewer',
                'phone_number' => '081234567892',
                'is_active' => true,
                'created_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['username' => $user['username']],
                $user
            );
        }
    }
}
