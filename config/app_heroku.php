<?php
# hoge
$db = parse_url(env('CLEARDB_DATABASE_URL'));
return [
    'debug' => false,
    'Security' => [
        'salt' => env('SALT'),
    ],
    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => $db['host'],
            'username' => $db['user'],
            'password' => $db['pass'],
            'database' => substr($db['path'], 1),
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
        ],
        's3' => [
            'className'  => 'CakeS3\Datasource\Connection',
            'key'        => 'AKIAICC6ZQALG747TI2A',
            'secret'     => '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',
            'bucketName' => 'login-faces',
            'region'     => 'us-east-1',
        ],
    ],
    'EmailTransport' => [
        'default' => [
            'className' => 'Smtp',
            // The following keys are used in SMTP transports
            'host' => 'ssl://' . $smtp['host'],
            'port' => $smtp['port'],
            'timeout' => 30,
            'username' => $smtp['user'],
            'password' => $smtp['pass'],
            'client' => null,
            'tls' => null,
        ],
    ],
    'Log' => [
        'debug' => [
            'className' => 'Cake\Log\Engine\ConsoleLog',
            'stream' => 'php://stdout',
            'levels' => ['notice', 'info', 'debug'],
        ],
        'error' => [
            'className' => 'Cake\Log\Engine\ConsoleLog',
            'stream' => 'php://stderr',
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
        ],
    ],
];