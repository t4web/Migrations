<?php

namespace T4web\MigrationsTest\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use T4web\Migrations\Version\Resolver;
use T4web\Migrations\Controller\ListController;
use T4web\Migrations\Controller\ListControllerFactory;

class ListControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);

        $controllerManager = $this->prophesize(ControllerManager::class);
        $controllerManager->getServiceLocator()->willReturn($serviceLocator);

        $resolver = $this->prophesize(Resolver::class);
        $serviceLocator->get(Resolver::class)->willReturn($resolver->reveal());

        $factory = new ListControllerFactory();

        $controller = $factory->createService($controllerManager->reveal());

        $this->assertTrue($controller instanceof ListController);
    }
}
