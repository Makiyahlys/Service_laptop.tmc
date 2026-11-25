<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        // 1. Kasir / Front Office
        User::updateOrCreate(
            ['email' => 'kasir@tmc.com'],
            [
                'name' => 'Siti Rahma (Kasir Loket)',
                'password' => $password,
                'role' => 'kasir',
                'phone' => '081234567891',
            ]
        );

        // 2. Teknisi
        User::updateOrCreate(
            ['email' => 'teknisi@tmc.com'],
            [
                'name' => 'Randi Kurniawan (Teknisi Senior)',
                'password' => $password,
                'role' => 'teknisi',
                'phone' => '081388776655',
            ]
        );

        // 3. Owner / Pimpinan
        User::updateOrCreate(
            ['email' => 'owner@tmc.com'],
            [
                'name' => 'H. Hendra Wijaya (Owner Toko TMC)',
                'password' => $password,
                'role' => 'owner',
                'phone' => '081122334455',
            ]
        );

        // 4. Admin Utama (Super Admin)
        User::updateOrCreate(
            ['email' => 'admin@tmc.com'],
            [
                'name' => 'Admin Utama TMC',
                'password' => $password,
                'role' => 'owner',
                'phone' => '081298765432',
            ]
        );
    }
}
