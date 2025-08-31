<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('outgoing_crisp_whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('request_id')->unique()->nullable();
            $table->boolean('error');
            $table->string('reason');
            $table->json('message_template');
            $table->json('response_data');
            $table->smallInteger('status_code');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outgoing_crisp_whatsapp_messages');
    }
};
