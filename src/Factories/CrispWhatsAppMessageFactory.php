<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Factories;

use Ziming\LaravelCrispWhatsApp\CrispWhatsAppMessage;
use Ziming\LaravelCrispWhatsApp\Data\CrispWhatsAppTemplate;
use Ziming\LaravelCrispWhatsApp\Enums\ComponentTypeEnum;
use Ziming\LaravelCrispWhatsApp\Enums\HeaderComponentFormatEnum;

class CrispWhatsAppMessageFactory
{
    public static function createFromTemplateArray(array $template, array $bodyParameters = [], array $headerParameters = []): CrispWhatsAppMessage
    {
        $crispWhatsAppMessage = CrispWhatsAppMessage::make()
            ->templateLanguage($template['language'])
            ->templateName($template['name']);

        foreach ($template['components'] as $component) {

            if ($component['type'] === ComponentTypeEnum::Header->value) {

                if ($component['format'] === HeaderComponentFormatEnum::Text->value) {
                    $crispWhatsAppMessage->addTemplateHeaderTextComponent(
                        $component['text'],
                        $headerParameters,
                    );
                } elseif ($component['format'] === HeaderComponentFormatEnum::Image->value) {
                    $crispWhatsAppMessage->addTemplateHeaderImageComponent(
                        $template['name'],
                        $component['example']['header_handle'][0],
                    );

                } elseif ($component['format'] === HeaderComponentFormatEnum::Location->value) {
                    // TODO: To Implement this of course. What else?

                } elseif ($component['format'] === HeaderComponentFormatEnum::Document->value) {
                    $crispWhatsAppMessage->addTemplateHeaderDocumentComponent(
                        $template['name'],
                        $component['example']['header_handle'][0],
                    );

                } elseif ($component['format'] === HeaderComponentFormatEnum::Video->value) {
                    $crispWhatsAppMessage->addTemplateHeaderVideoComponent(
                        $component['example']['header_handle'][0],
                    );
                }

            } elseif ($component['type'] === ComponentTypeEnum::Body->value) {

                $crispWhatsAppMessage->addTemplateBodyComponent(
                    $component['text'],
                    $bodyParameters,
                );

            } elseif ($component['type'] === ComponentTypeEnum::Buttons->value) {

                foreach ($component['buttons'] as $button) {
                    $crispWhatsAppMessage->addTemplateButtonComponent(
                        $button['text'],
                        $button['type'],
                        // TODO: How to handle button parameters?
                    );
                }

            } elseif ($component['type'] === ComponentTypeEnum::Footer->value) {

                $crispWhatsAppMessage->addTemplateFooterComponent(
                    $component['text'],
                );
            }
        }

        return $crispWhatsAppMessage;
    }

    public static function createFromTemplateObject(CrispWhatsAppTemplate $template): CrispWhatsAppMessage
    {
        return self::createFromTemplateArray(
            // ->all() will cast to the enums type which is not what we want here
            $template->toArray()
        );
    }
}
