<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Builders;

class ParameterBuilder
{

    public static function headerImage(string $fileName, string $link): array
    {
        return [
            'type' => 'image',
            'image' => [
                'filename' => $fileName,
                'link' => $link,
            ],
        ];
    }

    public static function headerVideo(string $link): array
    {
        return [
            'type' => 'video',
            'video' => [
                'link' => $link,
            ],
        ];
    }

    public static function headerDocument(string $fileName, string $link): array
    {
        return [
            'type' => 'document',
            'document' => [
                'filename' => $fileName,
                'link' => $link,
            ],
        ];
    }

    public static function headerLocation(string $name, string $address, float $latitude, float $longitude): array
    {
        return [
            'type' => 'location',
            'location' => [
                'name' => $name,
                'address' => $address,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
        ];
    }


    public static function text(string $text): array
    {
        return [
            'type' => 'text',
            'text' => $text,
        ];
    }

    public static function buttonFlow(string $flowToken, array $flowActionData): array
    {
        return [
            'type' => 'action',
            'action' => [
                'flow_token' => $flowToken,
                'flow_action_data' => $flowActionData,
            ],
        ];
    }

}
