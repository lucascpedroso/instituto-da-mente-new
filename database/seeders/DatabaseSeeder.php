<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ContentSeeder::class);

        // Em produção o administrador é criado com `php artisan make:filament-user`.
        if (! app()->isProduction()) {
            User::updateOrCreate(['email' => 'admin@example.com'], [
                'name' => 'Administrador (local)',
                'password' => 'password',
                'role' => 'admin',
            ]);
        }
    }
}
