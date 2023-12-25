<?php

namespace dmitryrogolev\Canis\Console\Commands;

use Illuminate\Console\Command;

/**
 * Команда установки пакета "Canis", предоставляющего функционал ролей и разрешений.
 */
class InstallCommand extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'canis:install 
                                {--config}
                                {--migrations}
                                {--seeders}';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Installs the "Canis" package that provides roles and permissions functionality for the Laravel framework.';

    /**
     * Выполнить консольную команду.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->install('is');
        $this->install('can');

        $tag = 'canis';

        if ($this->option('config')) {
            $tag .= '-config';
        } elseif ($this->option('seeders')) {
            $tag .= '-seeders';
        }

        $this->call('vendor:publish', ['--tag' => $tag]);
    }

    /**
     * Устанавливает пакет.
     */
    protected function install(string $package): void
    {
        $option = '';

        if ($this->option('config')) {
            return;
        } elseif ($this->option('migrations')) {
            $option = '--migrations';
        } elseif ($this->option('seeders')) {
            $option = '--seeders';
        }

        if ($option) {
            $this->call($package.':install', [$option => true]);
        } else {
            $this->call($package.':install');
        }
    }
}
