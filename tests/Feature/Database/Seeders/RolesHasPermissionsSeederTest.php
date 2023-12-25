<?php

namespace dmitryrogolev\Canis\Tests\Database\Seeders;

use dmitryrogolev\Canis\Tests\RefreshDatabase;
use dmitryrogolev\Canis\Tests\TestCase;
use dmitryrogolev\Is\Facades\Is;

/**
 * Тестируем сидер, добавляющий связи ролей с разрешениями.
 */
class RolesHasPermissionsSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Имя класса сидера.
     */
    protected string $relationSeeder;

    /**
     * Сидер ролей.
     */
    protected string $roleSeeder;

    /**
     * Сидер разрешений.
     */
    protected string $permissionSeeder;

    public function setUp(): void
    {
        parent::setUp();

        $this->relationSeeder = config('canis.seeders.roles_has_permissions');
        $this->roleSeeder = config('canis.seeders.role');
        $this->permissionSeeder = config('canis.seeders.permission');
    }

    /**
     * Создаются ли связи при запуске сидера?
     */
    public function test_run(): void
    {
        app($this->roleSeeder)->run();
        app($this->permissionSeeder)->run();
        app($this->relationSeeder)->run();
        $admin = Is::firstWhereUniqueKey('admin');

        $this->assertNotEmpty($admin->permissions);
    }
}
