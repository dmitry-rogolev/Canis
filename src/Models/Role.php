<?php

namespace dmitryrogolev\Canis\Models;

use dmitryrogolev\Can\Contracts\Permissionable;
use dmitryrogolev\Can\Traits\HasPermissions;
use dmitryrogolev\Is\Models\Role as Model;

class Role extends Model implements Permissionable
{
    use HasPermissions;
}
