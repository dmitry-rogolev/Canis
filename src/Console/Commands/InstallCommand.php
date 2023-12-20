<?php

namespace dmitryrogolev\Canis\Console\Commands;

use Illuminate\Console\Command;

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
        $this->publish('is');
        $this->publish('can');
        $this->publish('canis');
    }

    private function publish(string $tag): void
    {
        if ($this->option('config')) {
            $tag .= '-config';
        } elseif ($this->option('seeders')) {
            $tag .= '-seeders';
        }

        $this->call('vendor:publish', [
            '--tag' => $tag,
        ]);
    }
}
