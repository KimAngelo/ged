<?php
require __DIR__ . '/vendor/autoload.php';

return [
    'paths' => [
        'migrations' => [
            __DIR__ . '/database/migrations'
        ],
        'seeds' => [
            __DIR__ . '/database/seeds'
        ]
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => $_ENV['CONF_DB_DRIVER'],
            'host' => $_ENV['CONF_DB_HOST'],
            'name' => $_ENV['CONF_DB_NAME'],
            'user' => $_ENV['CONF_DB_USER'],
            'pass' => $_ENV['CONF_DB_PASS'],
            'charset' => $_ENV['CONF_DB_CHARSET'],
            'collation' => $_ENV['CONF_DB_COLLATION']
        ],
        'prod' => [
            'adapter' => $_ENV['CONF_DB_DRIVER_PROD'],
            'host' => $_ENV['CONF_DB_HOST_PROD'],
            'name' => $_ENV['CONF_DB_NAME_PROD'],
            'user' => $_ENV['CONF_DB_USER_PROD'],
            'pass' => $_ENV['CONF_DB_PASS_PROD'],
            'charset' => $_ENV['CONF_DB_CHARSET_PROD'],
            'collation' => $_ENV['CONF_DB_COLLATION_PROD']
        ]
    ]
];