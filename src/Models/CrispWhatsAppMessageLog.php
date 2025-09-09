<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Models;

use Illuminate\Database\Eloquent\Model;

class CrispWhatsAppMessageLog extends Model
{
    protected $table = 'crisp_whatsapp_message_logs';

    protected $guarded = [
        'id',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',

            'error' => 'boolean',
            'message_template' => 'array',
            'status_code' => 'integer',
            'response_data' => 'array',

            'callback_response_error' => 'boolean',
            'callback_response_data' => 'array',
        ];
    }
}
