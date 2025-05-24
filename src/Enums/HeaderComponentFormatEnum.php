<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum HeaderComponentFormatEnum: string
{
    case Text = 'text';
    case Image = 'image';
    case Video = 'video';
    case Document = 'document';
    case Location = 'location';
}
