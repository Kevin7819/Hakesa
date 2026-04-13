<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Remove any existing admin users to ensure clean state
        AdminUser::truncate();

        $password = Hash::make(env('SEEDER_PASSWORD', 'admin123'));

        AdminUser::create([
            'email' => 'admin@graciacreativa.com',
            'name' => 'Administrador Gracia Creativa',
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✓ 1 usuario admin creado (password: admin123)');
        $this->command->info('  - admin@graciacreativa.com');
    }
}
