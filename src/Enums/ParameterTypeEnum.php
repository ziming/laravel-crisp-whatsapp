<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum ParameterTypeEnum: string
{
    case Text = 'TEXT';

    case Image = 'IMAGE';
    case Video = 'VIDEO';
    case Document = 'DOCUMENT';

    case Action = 'ACTION';
    case Location = 'LOCATION';
}
