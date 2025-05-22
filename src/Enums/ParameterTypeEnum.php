<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum ParameterTypeEnum: string
{
    case Text = 'text';

    case Image = 'image';
    case Video = 'video';
    case Document = 'document';

    case Action = 'action';
    case Location = 'location';
}
