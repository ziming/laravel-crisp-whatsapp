<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Factories;

use Ziming\LaravelCrispWhatsApp\Enums\ButtonSubTypeEnum;
use Ziming\LaravelCrispWhatsApp\Enums\ComponentTypeEnum;
use Ziming\LaravelCrispWhatsApp\Enums\HeaderComponentFormatEnum;

final class ComponentFactory
{
    public static function headerText(string $text): array
    {
        return [
            'type' => ComponentTypeEnum::Header->value,
            'format' => HeaderComponentFormatEnum::Text->value,
            'text' => $text,
        ];
    }

    public static function headerImage(string $fileName, string $link): array
    {
        return [
            'type' => ComponentTypeEnum::Header->value,
            'format' => HeaderComponentFormatEnum::Image->value,
            'parameters' => [
                [
                    'type' => 'image',
                    'image' => [
                        'filename' => $fileName,
                        'link' => $link,
                    ],
                ],
            ],
        ];
    }

    public static function headerVideo(string $link): array
    {
        return [
            'type' => ComponentTypeEnum::Header->value,
            'format' => HeaderComponentFormatEnum::Video->value,
            'parameters' => [
                [
                    'type' => 'video',
                    'video' => [
                        'link' => $link,
                    ],
                ],
            ],
        ];
    }

    public static function headerDocument(string $fileName, string $link): array
    {
        return [
            'type' => ComponentTypeEnum::Header->value,
            'format' => HeaderComponentFormatEnum::Document->value,
            'parameters' => [
                [
                    'type' => 'document',
                    'document' => [
                        'filename' => $fileName,
                        'link' => $link,
                    ],
                ],
            ],
        ];
    }

    public static function headerLocation(string $name, string $address, float $latitude, float $longitude): array
    {
        return [
            'type' => ComponentTypeEnum::Header->value,
            'format' => HeaderComponentFormatEnum::Location->value,
            'parameters' => [
                [
                    'type' => 'location',
                    'location' => [
                        'name' => $name,
                        'address' => $address,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ],
                ],
            ],
        ];
    }

    public function body(string $bodyText, array $parameters = []): array
    {
        return [
            'type' => ComponentTypeEnum::Body->value,
            'text' => $bodyText,
            'parameters' => $parameters,
        ];
    }

    public function button(string $buttonText, string|ButtonSubTypeEnum $subType = ButtonSubTypeEnum::QuickReply, array $parameters = [], int $index = 0): array
    {
        return [
            'type' => 'BUTTON',
            'sub_type' => $subType,
            'text' => $buttonText,
            'index' => $index,
            'parameters' => $parameters,
        ];
    }

    public function footer(string $footerText): array
    {
        return [
            'type' => ComponentTypeEnum::Footer->value,
            'text' => $footerText,
        ];
    }
}
