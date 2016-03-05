<?php

namespace T4web\Migrations\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Migrations\Config;
use T4web\Migrations\MigrationVersion\Table;

class VersionResolverFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new VersionResolver(
            $serviceLocator->get(Config::class),
            $serviceLocator->get(Table::class)
        );
    }
}
