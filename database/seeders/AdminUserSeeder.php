<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@hakesa.com');
        $adminPassword = env('ADMIN_PASSWORD');
        $editorEmail = env('EDITOR_EMAIL', 'editor@hakesa.com');
        $editorPassword = env('EDITOR_PASSWORD');

        if (empty($adminPassword)) {
            $this->command->error('ADMIN_PASSWORD no está definido en .env. Definilo antes de correr el seeder.');
            $this->command->info('Ejemplo: ADMIN_PASSWORD=tu_password_seguro');

            return;
        }

        // Create Super Admin
        AdminUser::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($adminPassword),
                'role' => 'super-admin',
            ]
        );

        // Create Editor (optional, only if credentials provided)
        if ($editorEmail && $editorPassword) {
            AdminUser::firstOrCreate(
                ['email' => $editorEmail],
                [
                    'name' => 'Editor',
                    'password' => Hash::make($editorPassword),
                    'role' => 'editor',
                ]
            );
        }

        $this->command->info('✓ Admin users created successfully!');
    }
}
