<?php 

namespace dmitryrogolev\Canis\Traits;

use dmitryrogolev\Can\Traits\HasPermissions;
use dmitryrogolev\Is\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HasRolesAndPermissions 
{
    use HasRoles, HasPermissions {
        HasRoles::__call as callMagicRoles;
        HasPermissions::__call as callMagicPermissions;
        HasPermissions::attachPermission as parentAttachPermission;
        HasPermissions::detachPermission as parentDetachPermission;
        HasPermissions::detachAllPermissions as parentDetachAllPermissions;
    } 

    /**
     * Все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected $allPermissions = null;

    /**
     * Погрузить все разрешения.
     *
     * @return void
     */
    public function loadAllPermissions(): void 
    {
        $this->allPermissions = $this->unionPermissions()->get();
    }

    /**
     * Все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allPermissions(): Builder
    {
        return $this->unionPermissions();
    }

    /**
     * Возвращает все разрешения, которые есть непосредственно у текущей модели и у ролей данной модели
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPermissions(): Collection
    {
        if (! $this->allPermissions) {
            $this->loadAllPermissions();
        }

        return $this->allPermissions;
    }

    /**
     * Обнуляем поле с разрешениями
     *
     * @return void
     */
    public function resetAllPermissions(): void 
    {
        $this->allPermissions = null;
    }

    /**
     * Присоединить разрешения
     * 
     * Можно передавать идентификатор, slug или модель разрешения.
     * 
     * @param mixed ...$permission
     * @return bool
     */
    public function attachPermission(...$permission): bool 
    {
        if ($this->parentAttachPermission($permission)) {
            $this->resetAllPermissions();
            if (config('canis.uses.load_on_update')) {
                $this->loadAllPermissions();
            }
            return true;
        }

        return false;
    }

    /**
     * Отсоединить разрешения
     * 
     * Можно передавать идентификатор, slug или модель разрешения.
     * Если ничего не передовать, то будут отсоединены все отношения.
     * 
     * @param mixed ...$permission
     * @return bool
     */
    public function detachPermission(...$permission): bool 
    {
        if ($this->parentDetachPermission($permission)) {
            $this->resetAllPermissions();
            if (config('canis.uses.load_on_update')) {
                $this->loadAllPermissions();
            }
            return true;
        }

        return false;
    }

    /**
     * Отсоединить все разрешения 
     *
     * @return boolean
     */
    public function detachAllPermissions(): bool 
    {
        if ($this->parentDetachAllPermissions()) {
            $this->resetAllPermissions();
            if (config('canis.uses.load_on_update')) {
                $this->loadAllPermissions();
            }
            return true;
        }

        return false;
    }

    public function __call($method, $parameters) 
    {
        try {
            return parent::__call($method, $parameters);
        } catch (\BadMethodCallException $e) {
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
        if ($property === 'allPermissions') {
            return $this->getAllPermissions();
        }

        return parent::__get($property);
    }

    /**
     * Объединение разрешений текущей модели с разрешениями ролей
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function unionPermissions(): Builder
    {
        $query = $this->queryModelPermissions();

        if (config('canis.uses.levels')) {
            $roles = config('canis.models.role')::where('level', '<=', $this->level())->get();
        } else {
            $roles = $this->roles;
        }

        foreach ($roles as $role) {
            $query->union($this->queryRolePermissions($role));
        }

        return $query;
    }

    /**
     * Проверяем наличие разрешения
     *
     * @param mixed $permission
     * @return boolean
     */
    protected function checkPermission(mixed $permission): bool 
    {
        return $this->getAllPermissions()->contains(fn ($item) => $item->getKey() === $permission || $item->slug === $permission || $permission instanceof (config('canis.models.permission')) && $item->is($permission));
    }

    /**
     * Строим запрос на получения разрешений текущей модели
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryModelPermissions(): Builder
    {
        return $this->queryPermissions($this);
    }

    /**
     * Строим запрос на получения разрешений роли
     *
     * @param \Illuminate\Database\Eloquent\Model $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryRolePermissions(Model $role): Builder 
    {
        return $this->queryPermissions($role);
    }

    /**
     * Строим запрос на получение разрешений для указанной модели
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryPermissions(Model $model): Builder 
    {
        $permissionModel = app(config('canis.models.permission'));

        return config('canis.models.permission')::select(config('canis.tables.permissions').'.*')
            ->join(config('canis.tables.permissionables'), config('canis.tables.permissions').'.'.$permissionModel->getKeyName(), '=', config('canis.tables.permissionables').'.'.$permissionModel->getForeignKey())
            ->where(config('canis.tables.permissionables').'.'.config('canis.relations.permissionable').'_id', '=', $model->getKey())
            ->where(config('canis.tables.permissionables').'.'.config('canis.relations.permissionable').'_type', '=', $model::class);
    }
}