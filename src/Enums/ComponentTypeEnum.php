<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum ComponentTypeEnum: string
{
    case Header = 'HEADER';
    case Body = 'BODY';
    case Footer = 'FOOTER';
    case Buttons = 'BUTTONS';
}
