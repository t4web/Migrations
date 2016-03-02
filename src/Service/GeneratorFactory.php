<?php

namespace T4web\Migrations\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractPluginManager;

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
        if ($serviceLocator instanceof AbstractPluginManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $config = $serviceLocator->get('Config');

        if (!isset($config['migrations'])) {
            throw new \RuntimeException("Missing migrations configuration");
        }

        $migrationConfig = $config['migrations'];

        if (!isset($migrationConfig['dir'])) {
            throw new \RuntimeException("`dir` has not be specified in migrations configuration");
        }

        if (! isset($migrationConfig['namespace'])) {
            throw new \RuntimeException("`namespace` has not be specified in migrations configuration");
        }

        return new Generator($migrationConfig['dir'], $migrationConfig['namespace']);
    }
}
