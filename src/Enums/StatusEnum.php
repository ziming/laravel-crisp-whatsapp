<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum StatusEnum: string
{
    case Approved = 'approved';
    case Rejected = 'rejected';
    case In_Appeal = 'in_appeal';
    case Pending = 'pending';
    case Pending_Deletion = 'pending_deletion';
    case Deleted = 'deleted';
    case Disabled = 'disabled';
    case Paused = 'paused';
    case Limit_Exceeded = 'limit_exceeded';
}
