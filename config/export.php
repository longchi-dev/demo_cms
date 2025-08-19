<?php

return [
    'path' => env('EXPORT_PATH', 'export'),
    'batch_size' => env('APP_BATCH_SIZE', 1000),
    'cache_key' => 'exports:%s',
    'ttl' => 60,
];