<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Ziming\LaravelCrispWhatsApp\LaravelCrispWhatsAppServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelCrispWhatsAppServiceProvider::class,
        ];
    }
}
