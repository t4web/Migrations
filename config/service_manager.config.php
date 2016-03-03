<?php
namespace T4web\Migrations;

use Symfony\Component\Filesystem\Filesystem;

return [
    'factories' => [
        'T4web\Migrations\MigrationVersion\Table' => 'T4web\Migrations\MigrationVersion\TableFactory',
        'T4web\Migrations\MigrationVersion\TableGateway' => 'T4web\Migrations\MigrationVersion\TableGatewayFactory',
        'T4web\Migrations\Service\Migration' => 'T4web\Migrations\Service\MigrationFactory',
        'T4web\Migrations\Service\Generator' => 'T4web\Migrations\Service\GeneratorFactory',
        Config::class => ConfigFactory::class,
    ],
    'invokables' => [
        Filesystem::class => Filesystem::class,
    ],
];
