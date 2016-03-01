<?php

return [

    'migrations' => [
        'dir' => dirname(__FILE__) . '/../../../../migrations',
        'namespace' => 'T4web\Migrations',
        'adapter' => 'Zend\Db\Adapter\Adapter',
        'show_log' => true,
    ],

    'service_manager' => [
        'factories' => [
            'T4web\Migrations\MigrationVersion\Table' => 'T4web\Migrations\MigrationVersion\TableFactory',
            'T4web\Migrations\MigrationVersion\TableGateway' => 'T4web\Migrations\MigrationVersion\TableGatewayFactory',
            'T4web\Migrations\Service\Migration' => 'T4web\Migrations\Service\MigrationFactory',
            'T4web\Migrations\Service\Generator' => 'T4web\Migrations\Service\GeneratorFactory',
        ],
    ],

    'controllers' => [
        'factories' => [
            'T4web\Migrations\Controller\Migrate' => 'T4web\Migrations\Controller\MigrateControllerFactory'
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'migration-version' => [
                    'type' => 'simple',
                    'options' => [
                        'route' => 'migration version [--env=]',
                        'defaults' => [
                            '__NAMESPACE__' => 'T4web\Migrations\Controller',
                            'controller' => 'Migrate',
                            'action' => 'version'
                        ]
                    ]
                ],
                'migration-list' => [
                    'type' => 'simple',
                    'options' => [
                        'route' => 'migration list [--env=] [--all]',
                        'defaults' => [
                            '__NAMESPACE__' => 'T4web\Migrations\Controller',
                            'controller' => 'Migrate',
                            'action' => 'list'
                        ]
                    ]
                ],
                'migration-apply' => [
                    'type' => 'simple',
                    'options' => [
                        'route' => 'migration apply [<version>] [--env=] [--force] [--down] [--fake]',
                        'defaults' => [
                            '__NAMESPACE__' => 'T4web\Migrations\Controller',
                            'controller' => 'Migrate',
                            'action' => 'apply'
                        ]
                    ]
                ],
                'migration-generate' => [
                    'type' => 'simple',
                    'options' => [
                        'route' => 'migration generate [--env=]',
                        'defaults' => [
                            '__NAMESPACE__' => 'T4web\Migrations\Controller',
                            'controller' => 'Migrate',
                            'action' => 'generate'
                        ]
                    ]
                ]
            ]
        ]
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
