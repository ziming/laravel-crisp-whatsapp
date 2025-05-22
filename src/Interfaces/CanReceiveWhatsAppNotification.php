<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Interfaces;

use Illuminate\Notifications\Notification;

interface CanReceiveWhatsAppNotification
{
    public function routeNotificationForWhatsApp(Notification $notification): string;
}
