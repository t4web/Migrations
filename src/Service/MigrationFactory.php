<?php

namespace T4web\Migrations\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Migrations\MigrationVersion\Table;

class MigrationFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Migration(
            $serviceLocator->get(VersionResolver::class),
            $serviceLocator->get(Table::class),
            $serviceLocator->get('console'),
            $serviceLocator
        );
    }
}
