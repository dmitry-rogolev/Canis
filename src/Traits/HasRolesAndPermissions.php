<?php

namespace dmitryrogolev\Canis\Traits;

use BadMethodCallException;
use dmitryrogolev\Can\Traits\HasPermissions;
use dmitryrogolev\Is\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Функционал ролей и разрешений.
 */
trait HasRolesAndPermissions
{
    use HasPermissions {
        HasPermissions::__call as callMagicPermissions;
        HasPermissions::getPermissions as parentGetPermissions;
        HasPermissions::loadPermissions as parentLoadPermissions;
    }
    use HasRoles {
        HasRoles::__call as callMagicRoles;
        HasRoles::loadRoles as parentLoadRoles;
        HasRoles::isId insteadof HasPermissions;
        HasRoles::toFlattenArray insteadof HasPermissions;
        HasRoles::modelsToIds insteadof HasPermissions;
    }

    /**
     * Все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели.
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected $allPermissions = null;

    /**
     * Все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели.
     */
    public function allPermissions(): Builder
    {
        return $this->unionPermissions();
    }

    /**
     * Загружает все разрешения.
     */
    public function loadAllPermissions(): static
    {
        $this->allPermissions = $this->unionPermissions()->get();

        return $this;
    }

    /**
     * Подгружает отношение модели с ролями.
     */
    public function loadRoles(): static
    {
        $this->parentLoadRoles();

        if (config('canis.uses.all_permissions')) {
            $this->loadAllPermissions();
        }

        return $this;
    }

    /**
     * Подгружает разрешения.
     */
    public function loadPermissions(): static
    {
        $this->parentLoadPermissions();

        if (config('canis.uses.all_permissions')) {
            $this->loadAllPermissions();
        }

        return $this;
    }

    /**
     * Все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели.
     */
    public function getAllPermissions(): Collection
    {
        if (is_null($this->allPermissions)) {
            $this->loadAllPermissions();
        }

        return $this->allPermissions;
    }

    /**
     * Возвращает коллекцию разрешений.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model>
     */
    public function getPermissions(): Collection
    {
        return config('canis.uses.all_permissions') ? $this->getAllPermissions() : $this->parentGetPermissions();
    }

    /**
     * Обнуляет поле со всеми разрешениями.
     */
    public function resetAllPermissions(): static
    {
        $this->allPermissions = null;

        return $this;
    }

    public function __call($method, $parameters)
    {
        try {
            return parent::__call($method, $parameters);
        } catch (BadMethodCallException $e) {
            if (is_bool($is = $this->callMagicIsRole($method))) {
                return $is;
            }

            if (is_bool($can = $this->callMagicCanPermission($method))) {
                return $can;
            }

            throw $e;
        }
    }

    public function __get($property)
    {
        return $property === 'allPermissions' ? $this->getAllPermissions() : parent::__get($property);
    }

    /**
     * Объединяет запрос разрешений текущей модели с разрешениями ролей.
     */
    protected function unionPermissions(): Builder
    {
        $query = $this->queryModelPermissions();

        $roles = $this->getRoles();

        foreach ($roles as $role) {
            $query->union($this->queryRolePermissions($role));
        }

        return $query;
    }

    /**
     * Строит запрос на получения разрешений текущей модели.
     */
    protected function queryModelPermissions(): Builder
    {
        return $this->buildQueryPermissions($this);
    }

    /**
     * Строит запрос на получения разрешений роли.
     */
    protected function queryRolePermissions(Model $role): Builder
    {
        return $this->buildQueryPermissions($role);
    }

    /**
     * Строит запрос на получение разрешений для указанной модели.
     */
    protected function buildQueryPermissions(Model $model): Builder
    {
        $permission = app(config('canis.models.permission'));
        $permissionsTable = config('canis.tables.permissions');
        $permissionablesTable = config('canis.tables.permissionables');
        $relationName = config('canis.relations.permissionable');

        return $permission::select($permissionsTable.'.*')
            ->join($permissionablesTable, $permissionsTable.'.'.$permission->getKeyName(), '=', $permissionablesTable.'.'.$permission->getForeignKey())
            ->where($permissionablesTable.'.'.$relationName.'_id', '=', $model->getKey())
            ->where($permissionablesTable.'.'.$relationName.'_type', '=', $model::class);
    }
}
