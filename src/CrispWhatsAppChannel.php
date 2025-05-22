<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Ziming\LaravelCrispWhatsApp\Interfaces\CanReceiveWhatsAppNotification;

class CrispWhatsAppChannel
{
    private LaravelCrispWhatsApp $crispWhatsApp;

    public function __construct(LaravelCrispWhatsApp $crispWhatsApp)
    {
        $this->crispWhatsApp = $crispWhatsApp;
    }

    public function send(AnonymousNotifiable|CanReceiveWhatsAppNotification $notifiable, Notification $notification): void
    {
        /** @phpstan-ignore-next-line  $crispWhatsAppMessage */
        $crispWhatsAppMessage = $notification->toCrispWhatsApp($notifiable);

        /** @phpstan-ignore-next-line */
        $toPhone = $crispWhatsAppMessage->toNumber ?: $notifiable->routeNotificationForWhatsApp($notification);

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
