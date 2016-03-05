<?php

namespace T4web\Migrations\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Migrations\Version\Table;
use T4web\Migrations\Version\Resolver;

class MigrationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Migration(
            $serviceLocator->get(Resolver::class),
            $serviceLocator->get(Table::class),
            $serviceLocator->get('console'),
            $serviceLocator
        );
    }
}
