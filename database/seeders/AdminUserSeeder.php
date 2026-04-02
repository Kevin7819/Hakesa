<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::firstOrCreate(
            ['email' => 'admin@hakesa.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Hakesa2026!'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✓ Admin user creado.');
    }
}
