<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Interfaces;

interface CanReceiveCrispWhatsAppNotification
{
    public function routeNotificationForCrispWhatsApp(CrispWhatsAppNotification $notification): string;
}
