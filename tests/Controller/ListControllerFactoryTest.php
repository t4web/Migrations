<?php

namespace T4web\MigrationsTest\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use T4web\Migrations\Service\VersionResolver;
use T4web\Migrations\Controller\ListController;
use T4web\Migrations\Controller\ListControllerFactory;

class ListControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);

        $controllerManager = $this->prophesize(ControllerManager::class);
        $controllerManager->getServiceLocator()->willReturn($serviceLocator);

        $resolver = $this->prophesize(VersionResolver::class);
        $serviceLocator->get(VersionResolver::class)->willReturn($resolver->reveal());

        $factory = new ListControllerFactory();

        $controller = $factory->createService($controllerManager->reveal());

        $this->assertTrue($controller instanceof ListController);
    }
}
