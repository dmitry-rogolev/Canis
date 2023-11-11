<?php

namespace dmitryrogolev\Canis\Tests\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Запустить сидер
     */
    public function run(): void
    {
        $this->call([
            config('canis.seeders.role'), 
            config('canis.seeders.permission'), 
            config('canis.seeders.roles_has_permissions'), 
            \dmitryrogolev\Canis\Tests\Seeders\UserSeeder::class, 
        ]);
    }
}
