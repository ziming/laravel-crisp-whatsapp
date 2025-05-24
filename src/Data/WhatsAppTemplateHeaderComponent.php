<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Data;

use Spatie\LaravelData\Data;
use Ziming\LaravelCrispWhatsApp\Enums\ComponentTypeEnum;
use Ziming\LaravelCrispWhatsApp\Enums\HeaderComponentFormatEnum;

class WhatsAppTemplateHeaderComponent extends Data
{
    public function __construct(
        public ComponentTypeEnum|string $type,
        public HeaderComponentFormatEnum|string $format,
        public ?string $text = null, // only applicable if format is TEXT
        public ?array $example = null, // if it has parameters
    ) {}
}
