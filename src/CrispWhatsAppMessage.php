<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp;

use Ziming\LaravelCrispWhatsApp\Enums\ButtonSubTypeEnum;
use Ziming\LaravelCrispWhatsApp\Enums\CrispOptionTypeEnum;

class CrispWhatsAppMessage
{
    public function __construct(
        public array $messageTemplate = [],
        public array $crispOptions = [],
        public ?string $fromNumber = null,
        public ?string $toNumber = null,
    ) {
        $this->fromNumber = $fromNumber ?? config('crisp-whatsapp.from_phone');
    }

    public static function make(): self
    {
        return new self;
    }

    public function messageTemplate(array $messageTemplate): static
    {
        $this->messageTemplate = $messageTemplate;

        return $this;
    }

    public function templateLanguage(string $language): static
    {
        $this->messageTemplate['language'] = $language;

        return $this;
    }

    public function templateName(string $name): static
    {
        $this->messageTemplate['name'] = $name;

        return $this;
    }

    public function templateComponents(array $components): static
    {
        $this->messageTemplate['components'] = $components;

        return $this;
    }

    public function addTemplateHeaderTextComponent(string $headerText): static
    {
        $this->messageTemplate['components'][] = [
            'type' => 'header',
            'format' => 'text',
            'text' => $headerText,
        ];

        return $this;
    }

    public function addTemplateHeaderImageComponent(string $fileName, string $link): static
    {
        $this->messageTemplate['components'][] = [
            'type' => 'header',
            'format' => 'image',
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

    public function addTemplateHeaderVideoComponent(string $link): static
    {
        $this->messageTemplate['components'][] = [
            'type' => 'header',
            'format' => 'video',
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

    public function addTemplateHeaderDocumentComponent(string $fileName, string $link): static
    {
        $this->messageTemplate['components'][] = [
            'type' => 'header',
            'format' => 'document',
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

    public function addTemplateHeaderLocationComponent(string $name, string $address, float $latitude, float $longitude): static
    {
        $this->messageTemplate['components'][] = [
            'type' => 'header',
            'format' => 'location',
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

    public function addTemplateBodyComponent(string $bodyText, array $parameters = []): static
    {
        $this->messageTemplate['components'][] = [
            'type' => 'body',
            'text' => $bodyText,
            'parameters' => $parameters,
        ];

        return $this;
    }

    public function addTemplateButtonComponent(string $buttonText, string|ButtonSubTypeEnum $subType = ButtonSubTypeEnum::QuickReply, array $parameters = [], int $index = 0): static
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

    public function addTemplateFooter(string $footerText): static
    {
        $this->messageTemplate['components'][] = [
            'type' => 'FOOTER',
            'text' => $footerText,
        ];

        return $this;
    }

    public function crispOptions(CrispOptionTypeEnum $type = CrispOptionTypeEnum::Note, bool $newSession = false, bool $autoResolve = false): static
    {
        $this->crispOptions = [
            'type' => $type,
            'new_session' => $newSession,
            'auto_resolve' => $autoResolve,
        ];

        return $this;
    }

    public function setCrispOptions(array $crispOptions): static
    {
        $this->crispOptions = $crispOptions;

        return $this;
    }

    public function fromNumber(string $fromNumber): static
    {
        $this->fromNumber = $fromNumber;

        return $this;
    }

    public function toNumber(string $toNumber): static
    {
        $this->toNumber = $toNumber;

        return $this;
    }
}