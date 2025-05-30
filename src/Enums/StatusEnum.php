<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Enums;

enum StatusEnum: string
{
    case Approved = 'APPROVED';
    case Rejected = 'REJECTED';
    case In_Appeal = 'IN_APPEAL';
    case Pending = 'PENDING';
    case Pending_Deletion = 'PENDING_DELETION';
    case Deleted = 'DELETED';
    case Disabled = 'DISABLED';
    case Paused = 'PAUSED';
    case Limit_Exceeded = 'LIMIT_EXCEEDED';
}
