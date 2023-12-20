<?php

namespace dmitryrogolev\Canis\Tests;

use dmitryrogolev\Canis\Models\Role as Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Role extends Model
{
    /**
     * Роль относится к множеству пользователей
     */
    public function users(): MorphToMany
    {
        return $this->roleables(config('canis.models.user'));
    }
}
