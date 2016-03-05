<?php

namespace T4web\Migrations\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Migrations\Version\Resolver;

class ListControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        return new ListController(
            $serviceLocator->get(Resolver::class)
        );
    }
}
