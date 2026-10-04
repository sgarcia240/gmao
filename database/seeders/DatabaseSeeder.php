<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Administrador
        User::updateOrCreate(
            ['email' => 'admin@empresa.com'],
            [
                'name' => 'Sergio Administrador',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
            ]
        );

        // 2. Supervisor
        User::updateOrCreate(
            ['email' => 'supervisor@empresa.com'],
            [
                'name' => 'Jose Supervisor',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SUPERVISOR,
                'is_active' => true,
            ]
        );

        // 3. Técnico
        User::updateOrCreate(
            ['email' => 'tecnico@empresa.com'],
            [
                'name' => 'Juan Técnico',
                'password' => Hash::make('password'),
                'role' => User::ROLE_TECHNICIAN,
                'is_active' => true,
            ]
        );
    }
}