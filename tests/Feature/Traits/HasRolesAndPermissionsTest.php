<?php

namespace dmitryrogolev\Canis\Tests\Traits;

use dmitryrogolev\Canis\Tests\RefreshDatabase;
use dmitryrogolev\Canis\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Тестируем функционал ролей и разрешений.
 */
class HasRolesAndPermissionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Имя модели пользователя.
     */
    protected string $user;

    /**
     * Имя модели роли.
     */
    protected string $role;

    /**
     * Имя модели разрешения.
     */
    protected string $permission;

    /**
     * Имя первичного ключа.
     */
    protected string $keyName;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = config('canis.models.user');
        $this->role = config('canis.models.role');
        $this->permission = config('canis.models.permission');
        $this->keyName = config('canis.primary_key');

        config(['canis.uses.load_on_update' => true]);
        config(['canis.uses.levels' => false]);
    }

    /**
     * Есть ли метод, возвращающий запрос на получение всех разрешений?
     */
    public function test_all_permissions(): void
    {
        $user = $this->generate($this->user);
        $userPermissions = $this->generate($this->permission, 2);
        $user->attachPermission($userPermissions);

        $role = $this->generate($this->role);
        $rolePermissions = $this->generate($this->permission, 3);
        $role->attachPermission($rolePermissions);

        $user->attachRole($role);

        $expected = $userPermissions->concat($rolePermissions)->pluck($this->keyName)->all();

        // ! ||--------------------------------------------------------------------------------||
        // ! ||                  Подтверждаем получение построителя запросов.                  ||
        // ! ||--------------------------------------------------------------------------------||

        $builder = $user->allPermissions();
        $this->assertInstanceOf(Builder::class, $builder);

        // ! ||--------------------------------------------------------------------------------||
        // ! ||                     Подтверждаем получение всех разрешений.                    ||
        // ! ||--------------------------------------------------------------------------------||

        $actual = $user->allPermissions()->get()->pluck($this->keyName)->all();
        $this->assertEquals($expected, $actual);

        // ! ||--------------------------------------------------------------------------------||
        // ! ||               Подтверждаем количество выполненных запросов к БД.               ||
        // ! ||--------------------------------------------------------------------------------||

        $this->resetQueryExecutedCount();
        $actual = $user->allPermissions()->get()->pluck($this->keyName)->all();
        $this->assertQueryExecutedCount(1);
    }

    /**
     * Есть ли метод, возвращающий все разрешения?
     */
    public function test_get_all_permissions(): void
    {
        $user = $this->generate($this->user);
        $userPermissions = $this->generate($this->permission, 2);
        $user->attachPermission($userPermissions);

        $role = $this->generate($this->role);
        $rolePermissions = $this->generate($this->permission, 3);
        $role->attachPermission($rolePermissions);

        $user->attachRole($role);

        $expected = $userPermissions->concat($rolePermissions)->pluck($this->keyName)->all();

        // ! ||--------------------------------------------------------------------------------||
        // ! ||                         Подтверждаем возврат коллекции.                        ||
        // ! ||--------------------------------------------------------------------------------||

        $allPermissions = $user->getAllPermissions();
        $this->assertInstanceOf(Collection::class, $allPermissions);

        // ! ||--------------------------------------------------------------------------------||
        // ! ||                     Подтверждаем получение всех разрешений.                    ||
        // ! ||--------------------------------------------------------------------------------||

        $actual = $user->getAllPermissions()->pluck($this->keyName)->all();
        $this->assertEquals($expected, $actual);

        // ! ||--------------------------------------------------------------------------------||
        // ! ||               Подтверждаем количество выполненных запросов к БД.               ||
        // ! ||--------------------------------------------------------------------------------||

        $this->resetQueryExecutedCount();
        $actual = $user->getAllPermissions()->pluck($this->keyName)->all();
        $this->assertQueryExecutedCount(0);
    }

    /**
     * Есть ли свойство, возвращающее все разрешения?
     */
    public function test_all_permissions_property(): void
    {
        $user = $this->generate($this->user);
        $userPermissions = $this->generate($this->permission, 2);
        $user->attachPermission($userPermissions);

        $role = $this->generate($this->role);
        $rolePermissions = $this->generate($this->permission, 3);
        $role->attachPermission($rolePermissions);

        $user->attachRole($role);

        $expected = $userPermissions->concat($rolePermissions)->pluck($this->keyName)->all();

        // ! ||--------------------------------------------------------------------------------||
        // ! ||                         Подтверждаем возврат коллекции.                        ||
        // ! ||--------------------------------------------------------------------------------||

        $allPermissions = $user->allPermissions;
        $this->assertInstanceOf(Collection::class, $allPermissions);

        // ! ||--------------------------------------------------------------------------------||
        // ! ||                     Подтверждаем получение всех разрешений.                    ||
        // ! ||--------------------------------------------------------------------------------||

        $actual = $user->allPermissions->pluck($this->keyName)->all();
        $this->assertEquals($expected, $actual);

        // ! ||--------------------------------------------------------------------------------||
        // ! ||               Подтверждаем количество выполненных запросов к БД.               ||
        // ! ||--------------------------------------------------------------------------------||

        $this->resetQueryExecutedCount();
        $actual = $user->allPermissions->pluck($this->keyName)->all();
        $this->assertQueryExecutedCount(0);
    }

    /**
     * Есть ли метод, возвращающий все разрешения?
     */
    public function test_get_permissions(): void
    {
        config(['canis.uses.all_permissions' => true]);

        $user = $this->generate($this->user);
        $userPermissions = $this->generate($this->permission, 2);
        $user->attachPermission($userPermissions);

        $role = $this->generate($this->role);
        $rolePermissions = $this->generate($this->permission, 3);
        $role->attachPermission($rolePermissions);

        $user->attachRole($role);

        $expected = $userPermissions->concat($rolePermissions)->pluck($this->keyName)->all();

        // ! ||--------------------------------------------------------------------------------||
        // ! ||                         Подтверждаем возврат коллекции.                        ||
        // ! ||--------------------------------------------------------------------------------||

        $allPermissions = $user->getPermissions();
        $this->assertInstanceOf(Collection::class, $allPermissions);

        // ! ||--------------------------------------------------------------------------------||
        // ! ||                     Подтверждаем получение всех разрешений.                    ||
        // ! ||--------------------------------------------------------------------------------||

        $actual = $user->getPermissions()->pluck($this->keyName)->all();
        $this->assertEquals($expected, $actual);

        // ! ||--------------------------------------------------------------------------------||
        // ! ||               Подтверждаем количество выполненных запросов к БД.               ||
        // ! ||--------------------------------------------------------------------------------||

        $this->resetQueryExecutedCount();
        $actual = $user->getPermissions()->pluck($this->keyName)->all();
        $this->assertQueryExecutedCount(0);

        // ! ||--------------------------------------------------------------------------------||
        // ! ||            Подтверждаем возврат разрешений, присоединенных к модели.           ||
        // ! ||--------------------------------------------------------------------------------||

        config(['canis.uses.all_permissions' => false]);
        $expected = $userPermissions->pluck($this->keyName)->all();
        $actual = $user->getPermissions()->pluck($this->keyName)->all();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Есть ли метод, обнуляющий все разрешения?
     */
    public function test_reset_all_permissions(): void
    {
        $user = $this->generate($this->user);
        $userPermissions = $this->generate($this->permission, 2);
        $user->attachPermission($userPermissions);

        $role = $this->generate($this->role);
        $rolePermissions = $this->generate($this->permission, 3);
        $role->attachPermission($rolePermissions);

        $user->attachRole($role);

        // ! ||--------------------------------------------------------------------------------||
        // ! ||                      Подтверждаем возврат текущей модели.                      ||
        // ! ||--------------------------------------------------------------------------------||

        $self = $user->resetAllPermissions();
        $this->assertTrue($user->is($self));

        // ! ||--------------------------------------------------------------------------------||
        // ! ||                       Подтверждаем сброс всех разрешений.                      ||
        // ! ||--------------------------------------------------------------------------------||

        $user->loadAllPermissions();
        $this->resetQueryExecutedCount();
        $user->resetAllPermissions();
        $user->getAllPermissions();
        $this->assertQueryExecutedCount(1);
    }
}
