<?php

namespace dmitryrogolev\Canis\Tests\Feature\Console\Commands;

use dmitryrogolev\Canis\Tests\TestCase;

/**
 * Тестируем команду установки пакета "Canis".
 */
class InstallCommandTest extends TestCase
{
    /**
     * Запускается ли команда?
     */
    public function test_run(): void
    {
        $this->artisan('canis:install')->assertOk();
        $this->artisan('canis:install --config')->assertOk();
        $this->artisan('canis:install --migrations')->assertOk();
        $this->artisan('canis:install --seeders')->assertOk();
    }
}
