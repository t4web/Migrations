<?php

namespace T4web\Migrations;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Filesystem\Filesystem;

class ConfigFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $appConfig = $serviceLocator->get('Config');
        $filesystem = $serviceLocator->get(Filesystem::class);

        return new Config($appConfig, $filesystem);
    }
}
