<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp;

use Ziming\LaravelCrispWhatsApp\Enums\ButtonSubTypeEnum;
use Ziming\LaravelCrispWhatsApp\Enums\ComponentTypeEnum;
use Ziming\LaravelCrispWhatsApp\Enums\CrispOptionTypeEnum;
use Ziming\LaravelCrispWhatsApp\Enums\HeaderComponentFormatEnum;

final class CrispWhatsAppMessage
{
    public function __construct(
        public array $messageTemplate = [],
        public array $crispOptions = [],
        public ?string $toNumber = null,
        public ?string $fromNumber = null,
    ) {
        $this->fromNumber = $fromNumber ?? config('crisp-whatsapp.from_phone');
    }

    public static function make(): self
    {
        return new self;
    }

    public function messageTemplate(array $messageTemplate): self
    {
        $this->messageTemplate = $messageTemplate;

        return $this;
    }

    public function templateLanguage(string $language): self
    {
        $this->messageTemplate['language'] = $language;

        return $this;
    }

    public function templateName(string $name): self
    {
        $this->messageTemplate['name'] = $name;

        return $this;
    }

    public function templateComponents(array $components): self
    {
        $this->messageTemplate['components'] = $components;

        return $this;
    }

    public function addTemplateHeaderTextComponent(string $headerText): self
    {
        $this->messageTemplate['components'][] = [
            'type' => ComponentTypeEnum::Header,
            'format' => HeaderComponentFormatEnum::Text,
            'text' => $headerText,
        ];

        return $this;
    }

    public function addTemplateHeaderImageComponent(string $fileName, string $link): self
    {
        $this->messageTemplate['components'][] = [
            'type' => ComponentTypeEnum::Header,
            'format' => HeaderComponentFormatEnum::Image,
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

        return $this;
    }

    public function addTemplateHeaderVideoComponent(string $link): self
    {
        $this->messageTemplate['components'][] = [
            'type' => ComponentTypeEnum::Header,
            'format' => HeaderComponentFormatEnum::Video,
            'parameters' => [
                [
                    'type' => 'video',
                    'video' => [
                        'link' => $link,
                    ],
                ],
            ],
        ];

        return $this;
    }

    public function addTemplateHeaderDocumentComponent(string $fileName, string $link): self
    {
        $this->messageTemplate['components'][] = [
            'type' => ComponentTypeEnum::Header,
            'format' => HeaderComponentFormatEnum::Document,
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

        return $this;
    }

    public function addTemplateHeaderLocationComponent(string $name, string $address, float $latitude, float $longitude): self
    {
        $this->messageTemplate['components'][] = [
            'type' => ComponentTypeEnum::Header,
            'format' => HeaderComponentFormatEnum::Location,
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

        return $this;
    }

    public function addTemplateBodyComponent(string $bodyText, array $parameters = []): self
    {
        $this->messageTemplate['components'][] = [
            'type' => ComponentTypeEnum::Body,
            'text' => $bodyText,
            'parameters' => $parameters,
        ];

        return $this;
    }

    public function addTemplateButtonComponent(string $buttonText, string|ButtonSubTypeEnum $subType = ButtonSubTypeEnum::QuickReply, array $parameters = [], int $index = 0): self
    {
        $this->messageTemplate['components'][] = [
            'type' => 'button',
            'sub_type' => $subType,
            'text' => $buttonText,
            'index' => $index,
            'parameters' => $parameters,
        ];

        return $this;
    }

    public function addTemplateFooter(string $footerText): self
    {
        $this->messageTemplate['components'][] = [
            'type' => ComponentTypeEnum::Footer,
            'text' => $footerText,
        ];

        return $this;
    }

    public function crispOptions(CrispOptionTypeEnum $type = CrispOptionTypeEnum::Note, bool $newSession = false, bool $autoResolve = false): self
    {
        $this->crispOptions = [
            'type' => $type,
            'new_session' => $newSession,
            'auto_resolve' => $autoResolve,
        ];

        return $this;
    }

    public function setRawCrispOptions(array $crispOptions): self
    {
        $this->crispOptions = $crispOptions;

        return $this;
    }

    public function fromNumber(string $fromNumber): self
    {
        $this->fromNumber = $fromNumber;

        return $this;
    }

    public function toNumber(string $toNumber): self
    {
        $this->toNumber = $toNumber;

        return $this;
    }
}
