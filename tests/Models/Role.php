<?php

namespace dmitryrogolev\Canis\Tests\Models;

use dmitryrogolev\Is\Models\Role as Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Role extends Model
{
    /**
     * Роль относится ко множеству пользователей.
     */
    public function users(): MorphToMany
    {
        return $this->roleables(config('canis.models.user'));
    }
}
