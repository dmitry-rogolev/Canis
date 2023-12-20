<?php

namespace dmitryrogolev\Canis\Tests;

use dmitryrogolev\Canis\Contracts\Canisable;
use dmitryrogolev\Canis\Traits\HasCanis;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;

class BaseUser extends User implements Canisable
{
    use HasCanis, HasFactory;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('canis.connection');
        $this->table = 'users';
        $this->primaryKey = config('canis.primary_key');
        $this->timestamps = config('canis.uses.timestamps');
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
