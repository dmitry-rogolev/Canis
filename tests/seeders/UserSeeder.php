<?php

namespace dmitryrogolev\Canis\Tests\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Запустить сидер
     */
    public function run(): void
    {
        $adminRole = config('canis.models.role')::admin();
        $moderatorRole = config('canis.models.role')::moderator();
        $userRole = config('canis.models.role')::user();

        $admin = config('canis.models.user')::factory()->create();
        $admin->roles()->attach($adminRole);
        $admin->roles()->attach($moderatorRole);
        $admin->roles()->attach($userRole);

        for ($i = 0; $i < 3; $i++) {
            $moderator = config('canis.models.user')::factory()->create();
            $moderator->roles()->attach($moderatorRole);
            $moderator->roles()->attach($userRole);
        }

        for ($i = 0; $i < 10; $i++) {
            $user = config('canis.models.user')::factory()->create();
            $user->roles()->attach($userRole); 
        }

        $permissions = config('canis.models.permission')::all();
        $adminRole->attachPermission($permissions);

        $userPermissions = $permissions->where('model', 'User');
        $permissionPermissions = $permissions->where('model', 'Permission');

        $user = config('can.models.user')::factory()->create();
        foreach ($permissions as $permission) {
            $user->permissions()->attach($permission);
        }

        $user = config('can.models.user')::factory()->create();
        foreach ($userPermissions as $permission) {
            $user->permissions()->attach($permission);
        }

        $user = config('can.models.user')::factory()->create();
        foreach ($permissionPermissions as $permission) {
            $user->permissions()->attach($permission);
        }
    }
}
