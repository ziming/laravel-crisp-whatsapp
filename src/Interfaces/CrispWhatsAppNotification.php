<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Interfaces;

use Ziming\LaravelCrispWhatsApp\CrispWhatsAppMessage;

interface CrispWhatsAppNotification
{
    public function toCrispWhatsApp(CanReceiveCrispWhatsAppNotification $notifiable): CrispWhatsAppMessage;
}
