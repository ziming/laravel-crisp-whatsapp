<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Ziming\LaravelCrispWhatsApp\Commands\LaravelCrispWhatsAppCommand;

class LaravelCrispWhatsAppServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-crisp-whatsapp')
            ->hasConfigFile();
    }
}
