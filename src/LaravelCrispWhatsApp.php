<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Ziming\LaravelCrispWhatsApp\Data\CrispWhatsAppTemplate;
use Ziming\LaravelCrispWhatsApp\Data\WhatsAppTemplateBodyComponent;
use Ziming\LaravelCrispWhatsApp\Data\WhatsAppTemplateFooterComponent;
use Ziming\LaravelCrispWhatsApp\Data\WhatsAppTemplateHeaderComponent;
use Ziming\LaravelCrispWhatsApp\Enums\ComponentTypeEnum;

readonly class LaravelCrispWhatsApp
{
    private string $websiteId;

    private string $accessKeyIdentifier;

    private string $secretAccessKey;

    private string $fromPhone;

    public function __construct()
    {
        $this->websiteId = config('crisp-whatsapp.website_id');
        $this->accessKeyIdentifier = config('crisp-whatsapp.access_key_id');
        $this->secretAccessKey = config('crisp-whatsapp.secret_access_key');
        $this->fromPhone = config('crisp-whatsapp.from_phone');
    }

    public static function make(): self
    {
        return new self;
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplatesResponse(
        bool $onlyApproved = true,
        bool $excludeSamples = true,
        int $limit = 20,
        string $after = ''
    ): Response {
        return Http::withBasicAuth(
            $this->accessKeyIdentifier,
            $this->secretAccessKey
        )
            ->get(
                config('crisp-whatsapp.base_url').
                $this->websiteId.'/templates?'.
                "limit={$limit}&".
                "filter_approved={$onlyApproved}&".
                "filter_no_samples={$excludeSamples}&".
                "after={$after}" // The docs are not clear what it means
            );
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplates(
        bool $onlyApproved = true,
        bool $excludeSamples = true,
        int $limit = 20,
        string $after = ''

    ): array {

        return $this->getMessageTemplatesResponse($onlyApproved, $excludeSamples, $limit, $after)
            ->json();
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplate(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?CrispWhatsAppTemplate
    {
        $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        return CrispWhatsAppTemplate::from($messageTemplate);
    }
    /**
     * @throws ConnectionException
     */
    public function getMessageTemplateArray(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?array
    {
        $response = $this->getMessageTemplates($onlyApproved, $excludeSamples, $searchLimit, $after);

        $templates = Arr::get($response, 'data.templates');

        $pagingNext = Arr::get($response, 'data.paging_next');

        $messageTemplate = collect($templates)->first(function (array $template) use (&$name) {
            return $template['name'] === $name;
        });

        if ($messageTemplate === null && $pagingNext !== null) {
            sleep(1); // to not hit their rate limit
            $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $pagingNext);
        }

        return $messageTemplate;
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplateHeaderComponent(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?WhatsAppTemplateHeaderComponent
    {
        $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        foreach ($messageTemplate['components'] as $component) {
            if ($component['type'] === ComponentTypeEnum::Header->value) {
                return WhatsAppTemplateHeaderComponent::from($component);
            }
        }

        return null;
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplateBodyComponent(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?WhatsAppTemplateBodyComponent
    {
        $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        foreach ($messageTemplate['components'] as $component) {
            if ($component['type'] === ComponentTypeEnum::Body->value) {
                return WhatsAppTemplateBodyComponent::from($component);
            }
        }

        return null;
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplateButtonsComponent(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?WhatsAppTemplateFooterComponent
    {
        $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        foreach ($messageTemplate['components'] as $component) {
            if ($component['type'] === ComponentTypeEnum::Buttons->value) {
                return WhatsAppTemplateFooterComponent::from($component);
            }
        }

        return null;
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplateFooterComponent(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?WhatsAppTemplateFooterComponent
    {
        $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        foreach ($messageTemplate['components'] as $component) {
            if ($component['type'] === ComponentTypeEnum::Footer->value) {
                return WhatsAppTemplateFooterComponent::from($component);
            }
        }

        return null;
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplateHeaderText(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?string
    {
        return $this->getMessageTemplateHeaderComponent(
            $name,
            $searchLimit,
            $onlyApproved,
            $excludeSamples,
            $after
        )
            ?->text;
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplateBodyText(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?string
    {
        return $this->getMessageTemplateBodyComponent(
            $name,
            $searchLimit,
            $onlyApproved,
            $excludeSamples,
            $after
        )
            ?->text;
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplateFooterText(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?string
    {
        return $this->getMessageTemplateFooterComponent(
            $name,
            $searchLimit,
            $onlyApproved,
            $excludeSamples,
            $after
        )
            ?->text;
    }

    /**
     * @throws ConnectionException
     */
    public function sendMessageTemplate(
        string $toPhone,
        array $messageTemplate,
        array $crispOptions,
        ?string $fromPhone = null
    ): PromiseInterface|Response {

        $toPhone = config('crisp-whatsapp.test_mode') ? config('crisp-whatsapp.to_test_phone') : $toPhone;

        return Http::withBasicAuth(
            $this->accessKeyIdentifier,
            $this->secretAccessKey
        )
            ->post(config('crisp-whatsapp.base_url')."/{$this->websiteId}/template/send",
                [
                    'from_number' => $fromPhone ?: $this->fromPhone,
                    'to_number' => $toPhone,
                    'crisp_options' => $crispOptions,
                    'message_template' => $messageTemplate,
                ]);
    }
}
