<?php

namespace dmitryrogolev\Canis\Tests\Database\Factories;

use Orchestra\Testbench\Factories\UserFactory as TestbenchUserFactory;

/**
 * Фабрика модели пользователя.
 */
class UserFactory extends TestbenchUserFactory
{
    public function __construct(...$parameters)
    {
        parent::__construct(...$parameters);

        $this->model = config('canis.models.user');
    }
}
