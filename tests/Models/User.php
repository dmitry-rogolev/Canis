<?php

namespace dmitryrogolev\Canis\Tests\Models;

use dmitryrogolev\Canis\Contracts\RoleableAndPermissionable;
use dmitryrogolev\Canis\Tests\Database\Factories\UserFactory;
use dmitryrogolev\Canis\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Model;

/**
 * Модель пользователя.
 */
abstract class BaseUser extends Model implements RoleableAndPermissionable
{
    use HasFactory;
    use HasRolesAndPermissions;

    /**
     * Таблица БД, ассоциированная с моделью.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Атрибуты, для которых НЕ разрешено массовое присвоение значений.
     *
     * @var array<string>
     */
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setKeyName(config('canis.primary_key'));
        $this->timestamps = config('canis.uses.timestamps');
    }

    /**
     * Создайте новый экземпляр фабрики для модели.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}

if (config('canis.uses.uuid') && config('canis.uses.soft_deletes')) {
    class User extends BaseUser
    {
        use HasUuids, SoftDeletes;
    }
} elseif (config('canis.uses.uuid')) {
    class User extends BaseUser
    {
        use HasUuids;
    }
} elseif (config('canis.uses.soft_deletes')) {
    class User extends BaseUser
    {
        use SoftDeletes;
    }
} else {
    class User extends BaseUser
    {
    }
}
