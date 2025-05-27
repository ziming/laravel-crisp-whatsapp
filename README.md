# Laravel Crisp WhatsApp

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ziming/laravel-crisp-whatsapp.svg?style=flat-square)](https://packagist.org/packages/ziming/laravel-crisp-whatsapp)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ziming/laravel-crisp-whatsapp/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ziming/laravel-crisp-whatsapp/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ziming/laravel-crisp-whatsapp/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ziming/laravel-crisp-whatsapp/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ziming/laravel-crisp-whatsapp.svg?style=flat-square)](https://packagist.org/packages/ziming/laravel-crisp-whatsapp)

Send WhatsApp Notifications with Crisp!

This package is still in testing phase, so I do not recommend it for production use yet

## Support us

You can donate to my github sponsor account.

## Installation

You can install the package via composer:

```bash
composer require ziming/laravel-crisp-whatsapp
```

You may publish the config file with:

```bash
php artisan vendor:publish --tag="crisp-whatsapp-config"
```

This is the contents of the published config file:

```php
declare(strict_types=1);

return [
    'website_id' => env('CRISP_WEBSITE_ID'),

    'base_url' => env('CRISP_BASE_URL', 'https://plugins.crisp.chat/urn:crisp.im:whatsapp:0/wa/api/website/'),
    'access_key_id' => env('CRISP_WHATSAPP_ACCESS_KEY_ID'),
    'secret_access_key' => env('CRISP_WHATSAPP_SECRET_ACCESS_KEY'),
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
use Ziming\LaravelCrispWhatsApp\Factories\ComponentParameterFactory;
use Ziming\LaravelCrispWhatsApp\Factories\ComponentFactory;
use Ziming\LaravelCrispWhatsApp\Enums\ButtonSubTypeEnum;
use Ziming\LaravelCrispWhatsApp\LaravelCrispWhatsApp;

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
        $crispMessages = []
        
        // Example 1
        $crispMessages[] =  CrispWhatsAppMessage::make()
            ->templateLanguage('en')
            ->toNumber($notifiable->mobile_phone)
            ->templateName('template-name')
            ->addTemplateBodyComponent(
                // you may want to cache it if you can to hit Crisp API lesser!
                LaravelCrispWhatsApp::make()->getMessageTemplateBodyText('template-name'),
                [
                    ComponentParameterFactory::text('Crisp'),
                ]
            )
            ->addTemplateFooter('This is the footer of your whatsapp template')
            ->addTemplateButtonComponent('CTA', ButtonSubTypeEnum::Url)
            ->addTemplateButtonComponent('Not interested anymore');
            
           // Example 2
           $crispMessages[] =  CrispWhatsAppMessage::make()
                ->rawMessageTemplate([
                    'language' => 'en_US',
                    'name' => 'hello_world',
                    'components' => [
                        [
                            'type' => 'HEADER',
                            'FORMAT' => 'TEXT',
                            'text' => 'Hello World',
                        ],
                        [
                            'type' => 'BODY',
                            'text' => 'This is a body text',
                        ],
                        [
                            'type' => 'FOOTER',
                            'text' => 'This is a footer text',
                        ],
                    ],
                ]);
                
            // Example 3
            $crispMessages[] => CrispWhatsAppMessage::make()
                ->templateLanguage('en')
                ->toNumber($notifiable->mobile_phone)
                ->rawTemplateComponents([
                    ComponentFactory::headerText('Order #12345'),
                    ComponentFactory::body('{{1}} the Builder', ComponentParameterFactory::text('Crisp')),
                    ComponentFactory::button('Call To Action', ButtonSubTypeEnum::Url)
                    ComponentFactory::button('No', ButtonSubTypeEnum::QuickReply)
                    ComponentFactory::footer('This is the footer of your whatsapp template'),
                ]);
         
         return $crispMessages[random_int(0, 2)];
    }
}
```

Then in your model classes that can receive WhatsApp notifications, you can use the `CanReceiveWhatsAppNotification` trait:

Below is an example:

```php
use Illuminate\Database\Eloquent\Model;
use Ziming\LaravelCrispWhatsApp\Interfaces\CanReceiveCrispWhatsAppNotification;

class User extends Model implements CanReceiveCrispWhatsAppNotification
{
    public function routeNotificationForCrispWhatsApp(): string
    {
        return $this->attributes['mobile_phone'];
    }
}
```

## Why are the classes final?

This is selfish on my part, my hope is that it would incentivise you to make a pull request so that everyone would benefit. 
I will also get to know more about where my package is lacking especially in the early days of this library.

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
