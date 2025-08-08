<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum CategoryEnum: string
{
    case Authentication = 'AUTHENTICATION';
    case Marketing = 'MARKETING';
    case Utility = 'UTILITY';
}
