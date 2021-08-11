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
            'adapter' => $_ENV['CONF_DB_DRIVER'],
            'host' => $_ENV['CONF_DB_HOST'],
            'name' => $_ENV['CONF_DB_NAME'],
            'user' => $_ENV['CONF_DB_USER'],
            'pass' => $_ENV['CONF_DB_PASS'],
            'charset' => $_ENV['CONF_DB_CHARSET'],
            'collation' => $_ENV['CONF_DB_COLLATION']
        ]
    ]
];