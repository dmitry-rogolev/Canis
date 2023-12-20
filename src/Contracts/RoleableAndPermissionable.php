<?php

namespace dmitryrogolev\Canis\Contracts;

use dmitryrogolev\Can\Contracts\Permissionable;
use dmitryrogolev\Is\Contracts\Roleable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface RoleableAndPermissionable extends Permissionable, Roleable
{
    /**
     * Погрузить все разрешения.
     */
    public function loadAllPermissions(): void;

    /**
     * Все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели.
     */
    public function allPermissions(): Builder;

    /**
     * Возвращает все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели
     */
    public function getAllPermissions(): Collection;

    /**
     * Обнуляем поле с разрешениями
     */
    public function resetAllPermissions(): void;
}
