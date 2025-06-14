<?php

$url = getenv('DB_URL');

$components = parse_url($url);

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host={$components['host']};port={$components['port']};dbname=" . ltrim($components['path'], '/'),
    'username' => $components['user'],
    'password' => $components['pass'],
    'charset' => 'utf8',
];
