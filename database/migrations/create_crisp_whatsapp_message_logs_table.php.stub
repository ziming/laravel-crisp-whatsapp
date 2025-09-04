<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crisp_whatsapp_message_logs', function (Blueprint $table): void {
            $table->id();
            $table->uuid('request_id')->unique()->nullable();
            $table->boolean('error')->index();
            $table->string('reason')->index();
            $table->string('template_name')->index();
            $table->json('message_template');
            $table->smallInteger('status_code');

            // immediate response from the request
            // should i call it request_response_data? but that might be more confusing
            $table->json('response_data');

            // if there is a callback and matches the request id earlier. Only used if u use the same app for the
            // webhook callback
            $table->json('callback_response_data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crisp_whatsapp_message_logs');
    }
};
