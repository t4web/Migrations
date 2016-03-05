<?php
namespace T4web\Migrations;

use Symfony\Component\Filesystem\Filesystem;

return [
    'factories' => [
        MigrationVersion\Table::class => MigrationVersion\TableFactory::class,
        MigrationVersion\TableGateway::class => MigrationVersion\TableGatewayFactory::class,
        Service\Migration::class => Service\MigrationFactory::class,
        Service\Generator::class => Service\GeneratorFactory::class,
        Config::class => ConfigFactory::class,
        Service\VersionResolver::class => Service\VersionResolverFactory::class,
    ],
    'invokables' => [
        Filesystem::class => Filesystem::class,
    ],
];
