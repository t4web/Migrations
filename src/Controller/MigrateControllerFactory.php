<?php

namespace T4web\Migrations\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractPluginManager;

class MigrateControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if($serviceLocator instanceof AbstractPluginManager)
        {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $migration = $serviceLocator->get("T4web\\Migrations\\Service\\Migration");
        $generator = $serviceLocator->get("T4web\\Migrations\\Service\\Generator");

        return new MigrateController($migration, $generator);
    }
}
