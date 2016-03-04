<?php

namespace T4web\Migrations;

return [
    'factories' => [
        Controller\InitController::class => Controller\InitControllerFactory::class,
        Controller\VersionController::class => Controller\VersionControllerFactory::class,
        Controller\ListController::class => Controller\ListControllerFactory::class,
        Controller\ApplyController::class => Controller\ApplyControllerFactory::class,
        Controller\GenerateController::class => Controller\GenerateControllerFactory::class,
    ],
];
