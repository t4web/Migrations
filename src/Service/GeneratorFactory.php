<?php

namespace T4web\Migrations\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Filesystem\Filesystem;
use T4web\Migrations\Config;

class GeneratorFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get(Config::class);
        $filesystem = $serviceLocator->get(Filesystem::class);

        return new Generator($config, $filesystem);
    }
}
