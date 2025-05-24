<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Ziming\LaravelCrispWhatsApp\Interfaces\CanReceiveCrispWhatsAppNotification;
use Ziming\LaravelCrispWhatsApp\Interfaces\CrispWhatsAppNotification;

class CrispWhatsAppChannel
{
    private LaravelCrispWhatsApp $crispWhatsApp;

    public function __construct(LaravelCrispWhatsApp $crispWhatsApp)
    {
        $this->crispWhatsApp = $crispWhatsApp;
    }

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
