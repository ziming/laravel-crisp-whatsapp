<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum ButtonSubTypeEnum: string
{
    case Url = 'url';
    case Flow = 'flow';
    case Catalog = 'catalog';
    case QuickReply = 'quick_reply';
}
