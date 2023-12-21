<?php

namespace dmitryrogolev\Canis\Tests\Feature;

use dmitryrogolev\Canis\Tests\TestCase;
use Illuminate\Support\Arr;
use ReflectionClass;
use ReflectionMethod;

/**
 * Тестируем параметры конфигурации.
 */
class ConfigTest extends TestCase
{
    /**
     * Совпадает ли количество тестов с количеством переменных конфигурации?
     */
    public function test_count(): void
    {
        $FUNCTION = __FUNCTION__;
        $count = count(Arr::flatten(config('canis')));
        $methods = (new ReflectionClass($this))->getMethods(ReflectionMethod::IS_PUBLIC);
        $methods = array_values(array_filter($methods,
            fn ($method) => str_starts_with($method->name, 'test_') && $method->name !== $FUNCTION
        ));

        $this->assertCount($count, $methods);
    }

    /**
     * Есть ли конфигурация имени таблицы ролей?
     */
    public function test_tables_roles(): void
    {
        $this->assertTrue(is_string(config('canis.tables.roles')));
        $this->assertNotEmpty(config('canis.tables.roles'));
    }

    /**
     * Есть ли конфигурация имени промежуточной таблицы ролей?
     */
    public function test_tables_roleables(): void
    {
        $this->assertTrue(is_string(config('canis.tables.roleables')));
        $this->assertNotEmpty(config('canis.tables.roleables'));
    }

    /**
     * Есть ли конфигурация имени таблицы разрешений?
     */
    public function test_tables_permissions(): void
    {
        $this->assertTrue(is_string(config('canis.tables.permissions')));
        $this->assertNotEmpty(config('canis.tables.permissions'));
    }

    /**
     * Есть ли конфигурация имени промежуточной таблицы разрешений?
     */
    public function test_tables_permissionables(): void
    {
        $this->assertTrue(is_string(config('canis.tables.permissionables')));
        $this->assertNotEmpty(config('canis.tables.permissionables'));
    }

    /**
     * Есть ли конфигурация имени полиморфной связи промежуточной таблицы ролей?
     */
    public function test_relations_roleable(): void
    {
        $this->assertTrue(is_string(config('canis.relations.roleable')));
        $this->assertNotEmpty(config('canis.relations.roleable'));
    }

    /**
     * Есть ли конфигурация имени полиморфной связи промежуточной таблицы разрешений?
     */
    public function test_relations_permissionable(): void
    {
        $this->assertTrue(is_string(config('canis.relations.permissionable')));
        $this->assertNotEmpty(config('canis.relations.permissionable'));
    }

    /**
     * Есть ли конфигурация имени первичного ключа?
     */
    public function test_primary_key(): void
    {
        $this->assertTrue(is_string(config('canis.primary_key')));
        $this->assertNotEmpty(config('canis.primary_key'));
    }

    /**
     * Есть ли конфигурация разделителя строк?
     */
    public function test_separator(): void
    {
        $this->assertTrue(is_string(config('canis.separator')));
        $this->assertNotEmpty(config('canis.separator'));
    }

    /**
     * Есть ли конфигурация имени модели роли?
     */
    public function test_models_role(): void
    {
        $this->assertTrue(class_exists(config('canis.models.role')));
    }

    /**
     * Есть ли конфигурация имени модели промежуточной таблицы?
     */
    public function test_models_roleable(): void
    {
        $this->assertTrue(class_exists(config('canis.models.roleable')));
    }

    /**
     * Есть ли конфигурация имени модели разрешений?
     */
    public function test_models_permission(): void
    {
        $this->assertTrue(class_exists(config('canis.models.permission')));
    }

    /**
     * Есть ли конфигурация имени модели промежуточной таблицы?
     */
    public function test_models_permissionable(): void
    {
        $this->assertTrue(class_exists(config('canis.models.permissionable')));
    }

    /**
     * Есть ли конфигурация имени модели пользователя?
     */
    public function test_models_user(): void
    {
        $this->assertTrue(class_exists(config('canis.models.user')));
    }

    /**
     * Есть ли конфигурация имени фабрики модели роли?
     */
    public function test_factories_role(): void
    {
        $this->assertTrue(class_exists(config('canis.factories.role')));
    }

    /**
     * Есть ли конфигурация имени фабрики модели разрешений?
     */
    public function test_factories_permission(): void
    {
        $this->assertTrue(class_exists(config('canis.factories.permission')));
    }

    /**
     * Есть ли конфигурация имени сидера модели роли?
     */
    public function test_seeders_role(): void
    {
        $this->assertTrue(class_exists(config('canis.seeders.role')));
    }

    /**
     * Есть ли конфигурация имени сидера модели разрешений?
     */
    public function test_seeders_permission(): void
    {
        $this->assertTrue(class_exists(config('canis.seeders.permission')));
    }

    /**
     * Есть ли конфигурация имени сидера отношений ролей с разрешениями?
     *
     * @return void
     */
    public function test_seeders_roles_has_permissions(): void 
    {
        $this->assertTrue(class_exists(config('canis.seeders.roles_has_permissions')));
    }

    /**
     * Есть ли конфигурация флага использования UUID?
     */
    public function test_uses_uuid(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.uuid')));
    }

    /**
     * Есть ли конфигурация флага программного удаления моделей?
     */
    public function test_uses_soft_deletes(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.soft_deletes')));
    }

    /**
     * Есть ли конфигурация флага временных меток моделей?
     */
    public function test_uses_timestamps(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.timestamps')));
    }

    /**
     * Есть ли конфигурация флага регистрации миграций?
     */
    public function test_uses_migrations(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.migrations')));
    }

    /**
     * Есть ли конфигурация флага регистрации сидеров?
     */
    public function test_uses_seeders(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.seeders')));
    }

    /**
     * Есть ли конфигурация флага регистрации директив blade'а?
     */
    public function test_uses_blade(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.blade')));
    }

    /**
     * Есть ли конфигурация флага регистрации посредников?
     */
    public function test_uses_middlewares(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.middlewares')));
    }

    /**
     * Есть ли конфигурация флага подгрузки отношений после обновления?
     */
    public function test_uses_load_on_update(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.load_on_update')));
    }

    /**
     * Есть ли конфигурация флага расширения метода "is"?
     */
    public function test_uses_extend_is_method(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.extend_is_method')));
    }

    /**
     * Есть ли конфигурация флага расширения метода "can"?
     */
    public function test_uses_extend_can_method(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.extend_can_method')));
    }

    /**
     * Есть ли конфигурация флага использования иерархии ролей?
     */
    public function test_uses_levels(): void
    {
        $this->assertTrue(is_bool(config('canis.uses.levels')));
    }
}
