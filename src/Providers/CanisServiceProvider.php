<?php

namespace dmitryrogolev\Canis\Providers;

use dmitryrogolev\Canis\Console\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;

class CanisServiceProvider extends ServiceProvider
{
    /**
     * Имя тега пакета
     */
    private string $packageTag = 'canis';

    /**
     * Регистрация любых служб пакета.
     */
    public function register(): void
    {
        $this->mergeConfig();
        $this->loadMigrations();
        $this->publishFiles();
        $this->registerCommands();
    }

    /**
     * Загрузка любых служб пакета.
     */
    public function boot(): void
    {

    }

    /**
     * Объединяем конфигурацию пакета с конфигурацией приложения
     */
    private function mergeConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/canis.php', 'canis');
        $this->mergeConfigFrom(__DIR__.'/../../config/canis.php', 'can');
        $this->mergeConfigFrom(__DIR__.'/../../config/canis.php', 'is');
    }

    /**
     * Регистируем миграции пакета.
     */
    private function loadMigrations(): void
    {
        if (config('canis.uses.migrations')) {
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        }
    }

    /**
     * Публикуем файлы пакета
     */
    private function publishFiles(): void
    {
        $this->publishes([
            __DIR__.'/../../config/canis.php' => config_path('canis.php'),
        ], $this->packageTag.'-config');

        $this->publishes([
            __DIR__.'/../../database/seeders/publish' => database_path('seeders'),
        ], $this->packageTag.'-seeders');

        $this->publishes([
            __DIR__.'/../../config/canis.php' => config_path('canis.php'),
            __DIR__.'/../../database/seeders/publish' => database_path('seeders'),
        ], $this->packageTag);
    }

    /**
     * Регистрируем сидеры
     */
    private function loadSeedsFrom(): void
    {
        if (config('canis.uses.seeders')) {
            $this->app->afterResolving('seed.handler', function ($handler) {
                $handler->register(config('canis.seeders.roles_has_permissions'));
            });
        }
    }

    /**
     * Регистрируем комманды
     */
    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}