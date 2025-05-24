<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Data;

use Spatie\LaravelData\Data;
use Ziming\LaravelCrispWhatsApp\Enums\CategoryEnum;
use Ziming\LaravelCrispWhatsApp\Enums\StatusEnum;

class CrispWhatsAppTemplate extends Data
{
    public function __construct(
        public CategoryEnum|string $category,
        public string $id,
        public string $language,
        public string $name,
        public StatusEnum|string $status,
        public array $components,
        public QualityScore $quality_score,
    ) {}
}
