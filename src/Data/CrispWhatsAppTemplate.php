<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Data;

use Spatie\LaravelData\Data;
use Ziming\LaravelCrispWhatsApp\Enums\CategoryEnum;
use Ziming\LaravelCrispWhatsApp\Enums\ComponentTypeEnum;
use Ziming\LaravelCrispWhatsApp\Enums\StatusEnum;

final class CrispWhatsAppTemplate extends Data
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

    public function getHeaderComponent(): ?WhatsAppTemplateHeaderComponent
    {
        foreach ($this->components as $component) {
            if ($component['type'] === ComponentTypeEnum::Header->value) {
                return WhatsAppTemplateHeaderComponent::from($component);
            }
        }

        return null;
    }

    public function getBodyComponent(): ?WhatsAppTemplateBodyComponent
    {
        foreach ($this->components as $component) {
            if ($component['type'] === ComponentTypeEnum::Body->value) {
                return WhatsAppTemplateBodyComponent::from($component);
            }
        }

        return null;
    }

    public function getButtonsComponent(): ?WhatsAppTemplateButtonsComponent
    {
        foreach ($this->components as $component) {
            if ($component['type'] === ComponentTypeEnum::Buttons->value) {
                return WhatsAppTemplateButtonsComponent::from($component);
            }
        }

        return null;
    }
    public function getFooterComponent(): ?WhatsAppTemplateFooterComponent
    {
        foreach ($this->components as $component) {
            if ($component['type'] === ComponentTypeEnum::Footer->value) {
                return WhatsAppTemplateFooterComponent::from($component);
            }
        }

        return null;
    }
}
