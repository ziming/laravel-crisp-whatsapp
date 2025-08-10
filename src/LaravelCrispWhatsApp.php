<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Psr\SimpleCache\InvalidArgumentException;
use Ziming\LaravelCrispWhatsApp\Data\CrispWhatsAppTemplate;
use Ziming\LaravelCrispWhatsApp\Data\WhatsAppTemplateBodyComponent;
use Ziming\LaravelCrispWhatsApp\Data\WhatsAppTemplateFooterComponent;
use Ziming\LaravelCrispWhatsApp\Data\WhatsAppTemplateHeaderComponent;
use Ziming\LaravelCrispWhatsApp\Enums\ComponentTypeEnum;

final class LaravelCrispWhatsApp
{
    public function __construct(
        private ?string $websiteId = null,
        #[\SensitiveParameter]
        private ?string $accessKeyIdentifier = null,
        #[\SensitiveParameter]
        private ?string $secretAccessKey = null,
        private ?string $fromPhone = null,
    ) {
        $this->websiteId = $websiteId ?? config('crisp-whatsapp.website_id');
        $this->accessKeyIdentifier = $accessKeyIdentifier ?? config('crisp-whatsapp.access_key_id');
        $this->secretAccessKey = $secretAccessKey ?? config('crisp-whatsapp.secret_access_key');
        $this->fromPhone = $fromPhone ?? config('crisp-whatsapp.from_phone');
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
        return Http::baseUrl(config('crisp-whatsapp.base_url'))
            ->withBasicAuth(
                $this->accessKeyIdentifier,
                $this->secretAccessKey
            )
            ->get(
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
     * @throws InvalidArgumentException
     */
    public function getMessageTemplate(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?CrispWhatsAppTemplate
    {
        if (config()->boolean('crisp-whatsapp.enable_caching') === true && Cache::has("crisp_whatsapp_template:{$name}")) {
            return Cache::memo()->get("crisp_whatsapp_template:{$name}");
        }

        $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        $crispWhatsAppTemplate = CrispWhatsAppTemplate::from($messageTemplate);

        if (config()->boolean('crisp-whatsapp.enable_caching') === true) {
            Cache::memo()->put("crisp_whatsapp_template:{$name}", $crispWhatsAppTemplate, Carbon::now()->addHour());
        }

        return $crispWhatsAppTemplate;
    }

    /**
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    public function getMessageTemplateArray(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?array
    {
        if (config()->boolean('crisp-whatsapp.enable_caching') === true && Cache::has("crisp_whatsapp_template_array:{$name}")) {
            return Cache::memo()->get("crisp_whatsapp_template_array:{$name}");
        }

        $response = $this->getMessageTemplates($onlyApproved, $excludeSamples, $searchLimit, $after);

        $templates = Arr::get($response, 'data.templates');

        $pagingNext = Arr::get($response, 'data.paging_next');

        $messageTemplate = collect($templates)->first(function (array $template) use (&$name): bool {
            return $template['name'] === $name;
        });

        if ($messageTemplate === null && $pagingNext !== null) {
            sleep(1);
            $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $pagingNext);
        }

        if ($messageTemplate !== null && config()->boolean('crisp-whatsapp.enable_caching') === true) {
            Cache::memo()->put("crisp_whatsapp_template_array:{$name}", $messageTemplate, Carbon::now()->addHour());
        }

        return $messageTemplate;
    }

    /**
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    public function getMessageTemplateHeaderComponent(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?WhatsAppTemplateHeaderComponent
    {
        if (config()->boolean('crisp-whatsapp.enable_caching') === true && Cache::has("crisp_whatsapp_template_header_component:{$name}")) {
            return Cache::memo()->get("crisp_whatsapp_template_header_component:{$name}");
        }

        $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        foreach ($messageTemplate['components'] as $component) {
            if ($component['type'] === ComponentTypeEnum::Header->value) {

                $whatsAppTemplateHeaderComponent = WhatsAppTemplateHeaderComponent::from($component);

                if (config()->boolean('crisp-whatsapp.enable_caching') === true) {
                    Cache::memo()->put("crisp_whatsapp_template_header_component:{$name}", $whatsAppTemplateHeaderComponent, Carbon::now()->addHour());
                }

                return $whatsAppTemplateHeaderComponent;
            }
        }

        return null;
    }

    /**
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    public function getMessageTemplateBodyComponent(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?WhatsAppTemplateBodyComponent
    {
        if (config()->boolean('crisp-whatsapp.enable_caching') === true && Cache::has("crisp_whatsapp_template_body_component:{$name}")) {
            return Cache::memo()->get("crisp_whatsapp_template_body_component:{$name}");
        }

        $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        foreach ($messageTemplate['components'] as $component) {
            if ($component['type'] === ComponentTypeEnum::Body->value) {

                $whatsAppTemplateBodyComponent = WhatsAppTemplateBodyComponent::from($component);

                if (config()->boolean('crisp-whatsapp.enable_caching') === true) {
                    Cache::memo()->put("crisp_whatsapp_template_body_component:{$name}", $whatsAppTemplateBodyComponent, Carbon::now()->addHour());
                }

                return $whatsAppTemplateBodyComponent;
            }
        }

        return null;
    }

    /**
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    public function getMessageTemplateButtonsComponent(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?WhatsAppTemplateFooterComponent
    {
        if (config()->boolean('crisp-whatsapp.enable_caching') === true && Cache::has("crisp_whatsapp_template_buttons_component:{$name}")) {
            return Cache::memo()->get("crisp_whatsapp_template_buttons_component:{$name}");
        }

        $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        foreach ($messageTemplate['components'] as $component) {
            if ($component['type'] === ComponentTypeEnum::Buttons->value) {
                $whatsAppTemplateFooterComponent = WhatsAppTemplateFooterComponent::from($component);

                if (config()->boolean('crisp-whatsapp.enable_caching') === true) {
                    Cache::memo()->put("crisp_whatsapp_template_buttons_component:{$name}", $whatsAppTemplateFooterComponent, Carbon::now()->addHour());
                }

                return $whatsAppTemplateFooterComponent;
            }
        }

        return null;
    }

    /**
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    public function getMessageTemplateFooterComponent(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?WhatsAppTemplateFooterComponent
    {
        if (config()->boolean('crisp-whatsapp.enable_caching') === true && Cache::has("crisp_whatsapp_template_footer_component:{$name}")) {
            return Cache::memo()->get("crisp_whatsapp_template_footer_component:{$name}");
        }

        $messageTemplate = $this->getMessageTemplateArray($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        foreach ($messageTemplate['components'] as $component) {
            if ($component['type'] === ComponentTypeEnum::Footer->value) {

                $whatsAppTemplateFooterComponent = WhatsAppTemplateFooterComponent::from($component);

                if (config()->boolean('crisp-whatsapp.enable_caching') === true) {
                    Cache::memo()->put("crisp_whatsapp_template_footer_component:{$name}", $whatsAppTemplateFooterComponent, Carbon::now()->addHour());
                }

                return $whatsAppTemplateFooterComponent;
            }
        }

        return null;
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplateHeaderText(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?string
    {
        if (config()->boolean('crisp-whatsapp.enable_caching') === true) {
            return Cache::remember(
                "crisp_whatsapp_template_header_text:{$name}",
                Carbon::now()->addHour(),
                function () use (&$name, &$searchLimit, &$onlyApproved, &$excludeSamples, &$after): ?string {
                    return $this->getMessageTemplateHeaderComponent(
                        $name,
                        $searchLimit,
                        $onlyApproved,
                        $excludeSamples,
                        $after
                    )
                        ?->text;
                });
        }

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

        if (config()->boolean('crisp-whatsapp.enable_caching') === true) {
            return Cache::remember(
                "crisp_whatsapp_template_body_text:{$name}",
                Carbon::now()->addHour(),
                function () use (&$name, &$searchLimit, &$onlyApproved, &$excludeSamples, &$after): ?string {
                    return $this->getMessageTemplateBodyComponent(
                        $name,
                        $searchLimit,
                        $onlyApproved,
                        $excludeSamples,
                        $after
                    )?->text;
                }
            );
        }

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
        if (config()->boolean('crisp-whatsapp.enable_caching') === true) {
            return Cache::remember(
                "crisp_whatsapp_template_footer_text:{$name}",
                Carbon::now()->addHour(),
                function () use (&$name, &$searchLimit, &$onlyApproved, &$excludeSamples, &$after): ?string {
                    return $this->getMessageTemplateFooterComponent(
                        $name,
                        $searchLimit,
                        $onlyApproved,
                        $excludeSamples,
                        $after
                    )?->text;
                }
            );
        }

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
        array $messageTemplate,
        ?string $toPhone = null,
        array $crispOptions = ['type' => 'note'],
        ?string $fromPhone = null
    ): PromiseInterface|Response {

        $toPhone = config('crisp-whatsapp.test_mode') ? config('crisp-whatsapp.to_test_phone') : $toPhone;

        return Http::baseUrl(config('crisp-whatsapp.base_url'))
            ->withBasicAuth(
                $this->accessKeyIdentifier,
                $this->secretAccessKey
            )
            ->post("/{$this->websiteId}/template/send",
                [
                    'from_number' => $fromPhone ?: $this->fromPhone,
                    'to_number' => $toPhone,
                    'crisp_options' => $crispOptions,
                    'message_template' => $messageTemplate,
                ]);
    }
}
