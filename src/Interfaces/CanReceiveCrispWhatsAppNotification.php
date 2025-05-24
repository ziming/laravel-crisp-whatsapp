<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Interfaces;

use Illuminate\Notifications\Notification;

interface CanReceiveCrispWhatsAppNotification
{
    public function routeNotificationForCrispWhatsApp(Notification $notification): string;
}
