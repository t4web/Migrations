<?php

namespace T4web\Migrations\Version;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Migrations\Config;

class ResolverFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Resolver(
            $serviceLocator->get(Config::class),
            $serviceLocator->get(Table::class)
        );
    }
}
