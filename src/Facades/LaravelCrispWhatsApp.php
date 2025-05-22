<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ziming\LaravelCrispWhatsApp\LaravelCrispWhatsApp
 */
class LaravelCrispWhatsApp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ziming\LaravelCrispWhatsApp\LaravelCrispWhatsApp::class;
    }
}
