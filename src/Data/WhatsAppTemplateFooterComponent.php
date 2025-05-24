<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Data;

use Spatie\LaravelData\Data;
use Ziming\LaravelCrispWhatsApp\Enums\ComponentTypeEnum;

class WhatsAppTemplateFooterComponent extends Data
{
    public function __construct(
        public ComponentTypeEnum|string $type,
        public ?string $text,
    ) {}
}
