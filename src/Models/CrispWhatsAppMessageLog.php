<?php

declare(strict_types=1);

namespace Ziming\LaravelCrispWhatsApp\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Carbon;

class CrispWhatsAppMessageLog extends Model
{
    use Prunable;

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

    public function prunable(): Builder
    {
        return static::where('created_at', '<', Carbon::now()->subDays(
            config('crisp-whatsapp.delete_records_older_than_days')
        )
        );
    }
}
