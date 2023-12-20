<?php

namespace dmitryrogolev\Canis\Tests\Feature;

use dmitryrogolev\Canis\Tests\TestCase;

class HasRolesAndPermissionsTest extends TestCase
{
    /**
     * Проверяем наличие всех разрешений.
     */
    public function test_all_permissions(): void
    {
        $user = config('canis.models.role')::admin()->users()->first();
        $user->attachPermission(config('canis.models.permission')::factory(3)->create());
        if (! config('canis.uses.load_on_update')) {
            $user->loadAllPermissions();
        }

        $this->assertTrue($user->permissions->count() < $user->allPermissions->count());
        $this->assertTrue($user->permissions->count() < $user->getAllPermissions()->count());
    }

    /**
     * Проверяем наличие разрешения.
     */
    public function test_has_permission(): void
    {
        $user = config('canis.models.role')::admin()->users()->first();
        $user->attachPermission(config('canis.models.permission')::factory(3)->create());
        if (! config('canis.uses.load_on_update')) {
            $user->loadAllPermissions();
        }
        $permission = config('canis.models.role')::admin()->permissions()->first();

        $this->assertTrue($user->hasPermission($permission));
    }
}
