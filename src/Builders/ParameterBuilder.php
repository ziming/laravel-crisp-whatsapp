<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Builders;

class ParameterBuilder
{
    public function text(string $text): array
    {
        return [
            'type' => 'text',
            'text' => $text,
        ];
    }
}
