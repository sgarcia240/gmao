<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Administrador
        User::updateOrCreate(
            ['email' => 'admin@elevadores.com'],
            [
                'name' => 'Administrador GMAO',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Usuario Técnico
        User::updateOrCreate(
            ['email' => 'tecnico@elevadores.com'],
            [
                'name' => 'Juan Pérez (Técnico)',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
    }
}