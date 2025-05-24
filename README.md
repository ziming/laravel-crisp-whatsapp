# This is my package laravel-crisp-whatsapp

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ziming/laravel-crisp-whatsapp.svg?style=flat-square)](https://packagist.org/packages/ziming/laravel-crisp-whatsapp)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ziming/laravel-crisp-whatsapp/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ziming/laravel-crisp-whatsapp/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ziming/laravel-crisp-whatsapp/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ziming/laravel-crisp-whatsapp/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ziming/laravel-crisp-whatsapp.svg?style=flat-square)](https://packagist.org/packages/ziming/laravel-crisp-whatsapp)

Send WhatsApp Notifications with Crisp!

This package is not ready nor stable yet! I am still working on it.

## Support us

You can donate to my github sponsor account.

## Installation

You can install the package via composer:

```bash
composer require ziming/laravel-crisp-whatsapp
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-crisp-whatsapp-config"
```

This is the contents of the published config file:

```php
return [
    'website_id' => env('CRISP_WEBSITE_ID'),

    'base_url' => env('CRISP_BASE_URL', 'https://plugins.crisp.chat/urn:crisp.im:whatsapp:0/wa/api/website'),
    'identifier' => env('CRISP_WHATSAPP_IDENTIFIER'),
    'access_key' => env('CRISP_WHATSAPP_ACCESS_KEY'),
    'from_phone' => env('CRISP_WHATSAPP_FROM_PHONE'),

    // change it to false when you are ready for production
    'test_mode' => env('CRISP_WHATSAPP_TEST_MODE', true),

    // when test_mode is true, all whatsapp notifications will go to this number
    'to_test_phone' => env('CRISP_WHATSAPP_TO_TEST_PHONE'),
];
```

## Quick Usage (More documentation in the future!)

Here is an example on how you can use it in a laravel notification class.

```php
declare(strict_types=1);

use Ziming\LaravelCrispWhatsApp\Enums\ParameterTypeEnum;
use Ziming\LaravelCrispWhatsApp\CrispWhatsAppChannel;
use Ziming\LaravelCrispWhatsApp\Enums\ParameterTypeEnum;
use Ziming\LaravelCrispWhatsApp\CrispWhatsAppMessage;
use Ziming\LaravelCrispWhatsApp\Interfaces\CrispWhatsAppNotification;
use Ziming\LaravelCrispWhatsApp\CanReceiveCrispWhatsAppNotification;

class OrderShippedNotification extends Notification implements CrispWhatsAppNotification
{
    use Queueable;

    public function via(CanReceiveCrispWhatsAppNotification $notifiable): array
    {
        return [
            CrispWhatsAppChannel::class;
        ];
    }

    public function toCrispWhatsApp(CanReceiveCrispWhatsAppNotification $notifiable): CrispWhatsAppMessage
    {
        // See the source code for more methods on CrispWhatsAppMessage!
        return CrispWhatsAppMessage::make()
            ->templateLanguage('en')
            ->toNumber($notifiable->mobile_phone)
            ->templateName('template-name')
            ->addTemplateHeaderTextComponent('The header of your whatsapp template')
            ->addTemplateBodyComponent(
                // you may want to cache it if you can to hit Crisp API lesser!
                LaravelCrispWhatsApp::make()->getMessageTemplateBodyText('template-name'),
                [
                    [
                        'type' => ParameterTextEnum::Text,
                        'text' => 'Stranger',
                    ],
                ]
            )
            ->addTemplateFooter('This is the footer of your whatsapp template')
            ->addTemplateButtonComponent('CTA', 'URL')
            ->addTemplateButtonComponent('Not interested anymore');
    }
}
```

Then in your model classes that can receive WhatsApp notifications, you can use the `CanReceiveWhatsAppNotification` trait:

Below is an example:

```php
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Notifications\Notifiable;
use Ziming\LaravelCrispWhatsApp\Interfaces\CanReceiveCrispWhatsAppNotification;

class User extends Model implements CanReceiveCrispWhatsAppNotification
{
    public function routeNotificationForCrispWhatsApp(): string
    {
        return $this->mobile_phone;
    }
}
```

## Testing

Sending whatsapp messages costs money. Hence there are no tests. So if there are issues, just create an issue to let me know or make a PR fix for it :)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [ziming](https://github.com/ziming)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
