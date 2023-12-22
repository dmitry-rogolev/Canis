<?php

namespace dmitryrogolev\Canis\Database\Seeders;

use dmitryrogolev\Can\Facades\Can;
use dmitryrogolev\Is\Facades\Is;
use Illuminate\Database\Seeder;

/**
 * Добавляет связи ролей с разрешениями.
 */
class RolesHasPermissionsSeeder extends Seeder
{
    /**
     * Запустить сидер.
     */
    public function run(): void
    {
        $permissions = Can::all();
        $admin = Is::firstWhereUniqueKey('admin');

        if (! is_null($admin) && $permissions->isNotEmpty()) {
            foreach ($permissions as $permission) {
                $admin->permissions()->attach($permission);
            }
        }
    }
}
