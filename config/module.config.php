<?php

namespace T4web\Migrations;

return [

    'migrations' => [
        'dir' => dirname(__FILE__) . '/../../../../migrations',
        'namespace' => 'T4web\Migrations',
        'adapter' => 'Zend\Db\Adapter\Adapter',
        'show_log' => true,
    ],

    'service_manager' => require_once 'service_manager.config.php',
    'console' => require_once 'console.config.php',

    'controllers' => [
        'factories' => [
            'T4web\Migrations\Controller\Migrate' => 'T4web\Migrations\Controller\MigrateControllerFactory',
            Controller\InitController::class => Controller\InitControllerFactory::class,
        ],
    ],
];
