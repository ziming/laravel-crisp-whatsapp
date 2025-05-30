<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum HeaderComponentFormatEnum: string
{
    case Text = 'TEXT';
    case Image = 'IMAGE';
    case Video = 'VIDEO';
    case Document = 'DOCUMENT';
    case Location = 'LOCATION';
}
