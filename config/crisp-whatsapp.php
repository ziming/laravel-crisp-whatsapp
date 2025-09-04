<?php

declare(strict_types=1);

return [
    'website_id' => env('CRISP_WEBSITE_ID', ''),

    'base_url' => env('CRISP_BASE_URL', 'https://plugins.crisp.chat/urn:crisp.im:whatsapp:0/wa/api/website/'),
    'access_key_id' => env('CRISP_WHATSAPP_ACCESS_KEY_ID'),
    'secret_access_key' => env('CRISP_WHATSAPP_SECRET_ACCESS_KEY'),
    'from_phone' => env('CRISP_WHATSAPP_FROM_PHONE'),

    // change it to false when you are ready for production
    'test_mode' => env('CRISP_WHATSAPP_TEST_MODE', true),

    // when test_mode is true, all whatsapp notifications will go to this number
    'to_test_phone' => env('CRISP_WHATSAPP_TO_TEST_PHONE'),

    'enable_caching' => env('CRISP_WHATSAPP_ENABLE_CACHING', true),

    // if you want to log whatsapp requests, you will need to publish migration if this is true
    'log_requests' => env('CRISP_WHATSAPP_LOG_REQUESTS', false),
];
