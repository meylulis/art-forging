<?php

var_dump(getenv('DB_URL'));
exit;

$url = getenv('DB_URL'); // например, mysql://user:pass@host:port/dbname

// Парсим URL в компоненты
$components = parse_url($url);

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host={$components['host']};port={$components['port']};dbname=" . ltrim($components['path'], '/'),
    'username' => $components['user'],
    'password' => $components['pass'],
    'charset' => 'utf8',
];
