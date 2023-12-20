<?php

namespace dmitryrogolev\Canis\Database\Seeders;

use Illuminate\Database\Seeder;

class RolesHasPermissionsSeeder extends Seeder
{
    /**
     * Запустить сидер
     */
    public function run(): void
    {
        $permissions = config('canis.models.permission')::all();
        $admin = config('canis.models.role')::admin();

        foreach ($permissions as $permission) {
            $admin->permissions()->attach($permission);
        }
    }
}
