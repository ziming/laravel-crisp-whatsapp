<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Factories;

use Ziming\LaravelCrispWhatsApp\CrispWhatsAppMessage;
use Ziming\LaravelCrispWhatsApp\Enums\ComponentTypeEnum;
use Ziming\LaravelCrispWhatsApp\Enums\HeaderComponentFormatEnum;

class CrispWhatsAppMessageFactory
{
    public static function createFromTemplateArray(array $template): CrispWhatsAppMessage
    {
        $crispWhatsAppMessage = CrispWhatsAppMessage::make()
            ->templateLanguage($template['language'])
            ->templateName($template['name']);

        foreach ($template['components'] as $component) {

            if ($component['type'] === ComponentTypeEnum::Header->value) {

                if ($component['format'] === HeaderComponentFormatEnum::Image->value) {
                    $crispWhatsAppMessage->addTemplateHeaderImageComponent(
                        $template['name'],
                        $component['example']['header_handle'][0],
                    );

                }

            } elseif ($component['type'] === ComponentTypeEnum::Body->value) {

                $crispWhatsAppMessage->addTemplateBodyComponent(
                    $component['text'],
                    // TODO: figure out how to handle parameters later
                );

            } elseif ($component['type'] === ComponentTypeEnum::Buttons->value) {

                foreach ($component['buttons'] as $button) {
                    $crispWhatsAppMessage->addTemplateButtonComponent(
                        $button['text'],
                        $button['type'],
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
}
