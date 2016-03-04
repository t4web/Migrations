<?php

namespace T4web\Migrations;

return [
    'factories' => [
        'T4web\Migrations\Controller\Migrate' => 'T4web\Migrations\Controller\MigrateControllerFactory',
        Controller\InitController::class => Controller\InitControllerFactory::class,
        Controller\VersionController::class => Controller\VersionControllerFactory::class,
        Controller\ListController::class => Controller\ListControllerFactory::class,
        Controller\ApplyController::class => Controller\ApplyControllerFactory::class,
    ],
];
