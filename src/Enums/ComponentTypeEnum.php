<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum ComponentTypeEnum: string
{
    case Header = 'HEADER';
    case Body = 'BODY';
    case Footer = 'FOOTER';

    // When retrieving, it is BUTTONS
    case Buttons = 'BUTTONS';

    // When sending, it is BUTTON. No idea why the inconsistency.
    case Button = 'BUTTON';
}
