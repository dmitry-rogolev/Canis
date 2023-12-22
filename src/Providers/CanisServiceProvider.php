<?php

namespace dmitryrogolev\Canis\Providers;

use dmitryrogolev\Can\Providers\CanServiceProvider;
use dmitryrogolev\Canis\Console\Commands\InstallCommand;
use dmitryrogolev\Is\Providers\IsServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Config;
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
        $this->app->register(IsServiceProvider::class);
        $this->app->register(CanServiceProvider::class);

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

        // Заменяем конфигурацию пакетов Is и Can на собственную.
        $config = config()->all();
        $config['is'] =& $config['canis'];
        $config['can'] =& $config['canis'];
        $this->app->bind('config', function ($app) use ($config) {
            return new Repository($config);
        });
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
