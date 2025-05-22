<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum ComponentTypeEnum: string
{
    case Header = 'header';
    case Body = 'body';
    case Footer = 'footer';
    case Button = 'button';
}
