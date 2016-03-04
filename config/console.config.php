<?php

namespace T4web\Migrations;

return [
    'router' => [
        'routes' => [
            'migration-init' => [
                'type' => 'simple',
                'options' => [
                    'route' => 'migration init',
                    'defaults' => [
                        'controller' => Controller\InitController::class,
                    ]
                ]
            ],
            'migration-version' => [
                'type' => 'simple',
                'options' => [
                    'route' => 'migration version [--env=]',
                    'defaults' => [
                        'controller' => Controller\VersionController::class,
                    ]
                ]
            ],
            'migration-list' => [
                'type' => 'simple',
                'options' => [
                    'route' => 'migration list [--env=] [--all]',
                    'defaults' => [
                        'controller' => Controller\ListController::class,
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
];
