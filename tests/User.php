<?php

namespace dmitryrogolev\Canis\Tests;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

if (config('canis.uses.uuid') && config('canis.uses.soft_deletes')) {
    class User extends BaseUser
    {
        use HasUuids, SoftDeletes;
    }
} else if (config('canis.uses.uuid')) {
    class User extends BaseUser
    {
        use HasUuids;
    }
} else if (config('canis.uses.soft_deletes')) {
    class User extends BaseUser
    {
        use SoftDeletes;
    }
} else {
    class User extends BaseUser
    {
        
    }
}