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
                    'route' => 'migration apply [<version>] [--env=] [--force] [--down]',
                    'defaults' => [
                        'controller' => Controller\ApplyController::class,
                    ]
                ]
            ],
            'migration-generate' => [
                'type' => 'simple',
                'options' => [
                    'route' => 'migration generate [--env=]',
                    'defaults' => [
                        'controller' => Controller\GenerateController::class,
                    ]
                ]
            ]
        ]
    ]
];
