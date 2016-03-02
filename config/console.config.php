<?php

return [
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
];
