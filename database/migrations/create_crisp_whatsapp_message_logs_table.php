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
            $table->boolean('error');
            $table->string('reason');
            $table->json('message_template');
            $table->json('response_data');
            $table->smallInteger('status_code');

            // if there is a callback and matches the request id earlier.
            $table->json('callback_response_data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crisp_whatsapp_message_logs');
    }
};
