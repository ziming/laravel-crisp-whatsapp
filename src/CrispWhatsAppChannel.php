<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Notifications\AnonymousNotifiable;
use Ziming\LaravelCrispWhatsApp\Interfaces\CanReceiveCrispWhatsAppNotification;
use Ziming\LaravelCrispWhatsApp\Interfaces\CrispWhatsAppNotification;

class CrispWhatsAppChannel
{
    public function __construct(private LaravelCrispWhatsApp $crispWhatsApp)
    {}

    /**
     * @throws ConnectionException
     */
    public function send(AnonymousNotifiable|CanReceiveCrispWhatsAppNotification $notifiable, CrispWhatsAppNotification $notification): void
    {
        $crispWhatsAppMessage = $notification->toCrispWhatsApp($notifiable);

        $toPhone = $crispWhatsAppMessage->toNumber ?: $notifiable->routeNotificationForCrispWhatsApp($notification);

        // By default it is note, just making it explicit so that if Crisp changes the default in the future
        // I will not get surprised
        $crispOptions = $crispWhatsAppMessage->crispOptions ?: [
            'type' => 'note',
        ];

        $this->crispWhatsApp->sendMessageTemplate(
            $toPhone,
            $crispWhatsAppMessage->messageTemplate,
            $crispOptions
        );
    }
}
