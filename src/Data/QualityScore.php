<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Data;

use Spatie\LaravelData\Data;

class QualityScore extends Data
{
    public function __construct(public string $score) {}
}
