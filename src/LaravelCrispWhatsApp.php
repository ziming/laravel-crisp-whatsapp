<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class LaravelCrispWhatsApp 
{
    private string $websiteId;

    private string $identifier;

    private string $key;

    private string $fromPhone;

    public function __construct()
    {
        $this->websiteId = config('crisp-whatsapp.website_id');
        $this->identifier = config('crisp-whatsapp.identifier');
        $this->key = config('crisp-whatsapp.key');
        $this->fromPhone = config('crisp-whatsapp.from_phone');
    }

    public static function make(): self
    {
        return new self;
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
        $response = Http::withBasicAuth(
            $this->identifier,
            $this->key
        )
            ->get(
                config('crisp-whatsapp.base_url').
                $this->websiteId.'/templates?'.
                "limit={$limit}&".
                "filter_approved={$onlyApproved}&".
                "filter_no_samples={$excludeSamples}&".
                "after={$after}" // The docs are not clear what it means
            );

        return $response->json();
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplate(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?array
    {
        $response = $this->getMessageTemplates($onlyApproved, $excludeSamples, $searchLimit, $after);

        $templates = Arr::get($response, 'data.templates');

        $pagingNext = Arr::get($response, 'data.paging_next');

        $messageTemplate = collect($templates)->first(function (array $template) use (&$name) {
            return $template['name'] === $name;
        });

        if ($messageTemplate === null && $pagingNext !== null) {
            sleep(1); // to not hit their rate limit
            $messageTemplate = $this->getMessageTemplate($name, $searchLimit, $onlyApproved, $excludeSamples, $pagingNext);
        }

        return $messageTemplate;
    }

    /**
     * @throws ConnectionException
     */
    public function getMessageTemplateBodyContent(string $name, int $searchLimit = 20, bool $onlyApproved = true, bool $excludeSamples = true, string $after = ''): ?string
    {
        $messageTemplate = $this->getMessageTemplate($name, $searchLimit, $onlyApproved, $excludeSamples, $after);

        if ($messageTemplate === null) {
            return null;
        }

        foreach ($messageTemplate['components'] as $component) {
            if ($component['type'] === 'BODY') {
                return $component['text'];
            }
        }

        return null;
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
        $response = Http::withBasicAuth(
            $this->identifier,
            $this->key
        )
            ->post(config('crisp-whatsapp.base_url') . "/{$this->websiteId}/template/send",
                [
                    'from_number' => $fromPhone ?: $this->fromPhone,
                    'to_number' => $toPhone,
                    'crisp_options' => $crispOptions,
                    'message_template' => $messageTemplate,
                ]);

        return $response;
    }
}
