<?php 

namespace dmitryrogolev\Canis\Tests;

use dmitryrogolev\Can\Providers\CanServiceProvider;
use dmitryrogolev\Canis\Providers\CanisServiceProvider;
use dmitryrogolev\Is\Providers\IsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase 
{
    use RefreshDatabase;

    /**
     * Получить поставщиков пакета
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            CanisServiceProvider::class, 
            IsServiceProvider::class, 
            CanServiceProvider::class, 
        ];
    }
}
