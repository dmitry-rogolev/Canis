<?php

namespace dmitryrogolev\Canis\Contracts;

use dmitryrogolev\Can\Contracts\Permissionable;
use dmitryrogolev\Is\Contracts\Roleable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Функционал ролей и разрешений.
 */
interface RoleableAndPermissionable extends Permissionable, Roleable
{
    /**
     * Все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели.
     */
    public function allPermissions(): Builder;

    /**
     * Загружает все разрешения.
     */
    public function loadAllPermissions(): static;

    /**
     * Все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели.
     */
    public function getAllPermissions(): Collection;

    /**
     * Обнуляет поле со всеми разрешениями.
     */
    public function resetAllPermissions(): static;
}
