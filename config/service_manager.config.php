<?php
namespace T4web\Migrations;

use Symfony\Component\Filesystem\Filesystem;

return [
    'factories' => [
        Version\Table::class => Version\TableFactory::class,
        'T4web\Migrations\Version\TableGateway' => Version\TableGatewayFactory::class,
        Service\Migration::class => Service\MigrationFactory::class,
        Service\Generator::class => Service\GeneratorFactory::class,
        Config::class => ConfigFactory::class,
        Version\Resolver::class => Version\ResolverFactory::class,
    ],
    'invokables' => [
        Filesystem::class => Filesystem::class,
    ],
];
