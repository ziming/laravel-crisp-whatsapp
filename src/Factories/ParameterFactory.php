<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Factories;

class ParameterFactory
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

    /**
     * @param string $flowToken The flow token used as an identifier defaults to unused. Used when sub_type is flow.
     * @param array $flowActionData A json object with the data payload for the first screen. Used when sub_type is flow.
     * @param string|null $thumbnailProductRetailerId Item SKU number, labeled as Content ID in the Commerce Manager. Required when sub_type is catalog.
     */
    public static function buttonFlow(string $flowToken, array $flowActionData, ?string $thumbnailProductRetailerId = null): array
    {
        return [
            'type' => 'action',
            'action' => [
                'thumbnail_product_retailer_id' => $thumbnailProductRetailerId,
                'flow_token' => $flowToken,
                'flow_action_data' => $flowActionData,
            ],
        ];
    }
}
