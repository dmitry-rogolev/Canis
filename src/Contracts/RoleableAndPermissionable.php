<?php 

namespace dmitryrogolev\Canis\Contracts;

use dmitryrogolev\Can\Contracts\Permissionable;
use dmitryrogolev\Is\Contracts\Roleable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface RoleableAndPermissionable extends Roleable, Permissionable 
{
    /**
     * Погрузить все разрешения.
     *
     * @return void
     */
    public function loadAllPermissions(): void;

    /**
     * Все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allPermissions(): Builder;

    /**
     * Возвращает все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPermissions(): Collection;

    /**
     * Обнуляем поле с разрешениями
     *
     * @return void
     */
    public function resetAllPermissions(): void;
}
