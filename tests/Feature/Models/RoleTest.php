<?php

namespace dmitryrogolev\Canis\Tests\Feature\Models;

use dmitryrogolev\Can\Contracts\Permissionable;
use dmitryrogolev\Canis\Tests\TestCase;

/**
 * Тестируем модель роли.
 */
class RoleTest extends TestCase
{
    /**
     * Имя модели.
     */
    protected string $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = config('canis.models.role');
    }

    /**
     * Реализует ли модель роли функционал разрешений?
     */
    public function test_implements_permissionable(): void
    {
        $this->assertInstanceOf(Permissionable::class, app($this->model));
    }
}
