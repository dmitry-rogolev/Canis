<?php

namespace dmitryrogolev\Canis\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;

trait RefreshDatabase
{
    use TestingRefreshDatabase;

    /**
     * Главный сидер, который запускает другие сидеры
     */
    protected string $seeder = \dmitryrogolev\Canis\Tests\Seeders\DatabaseSeeder::class;

    /**
     * Следует ли запускать сидеры после миграции
     */
    protected bool $seed = true;

    /**
     * Определите миграцию базы данных
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(
            __DIR__.'/migrations'
        );
    }
}
