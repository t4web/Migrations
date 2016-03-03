<?php

namespace T4web\Migrations\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Migrations\Service\Migration;

class VersionControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        return new VersionController(
            $serviceLocator->get(Migration::class)
        );
    }
}
