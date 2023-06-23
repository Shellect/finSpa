<?php

return [
    'secret_key' => 'secret',
    'driver' => 'mysql',
    'host' => 'db',
    'username' => 'root',
    'password' => '12345678',
    'dbname' => 'fin_spa',
    'app_dir' => __DIR__ . '/app',
    'template_dir' => __DIR__ . '/templates/',
    'template_cache_path' => 'cache/',
    'template_cache_enabled' => false,
    'static_path' => 'static/',
    'pretty_url' => false,
];
