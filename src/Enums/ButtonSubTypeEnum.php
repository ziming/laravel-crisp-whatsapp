<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum ButtonSubTypeEnum: string
{
    case Url = 'URL';
    case Flow = 'FLOW';
    case Catalog = 'CATALOG';
    case QuickReply = 'QUICK_REPLY';
}
