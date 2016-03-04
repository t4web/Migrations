<?php

namespace T4web\MigrationsTest\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use T4web\Migrations\Service\Generator;
use T4web\Migrations\Controller\GenerateController;
use T4web\Migrations\Controller\GenerateControllerFactory;

class GenerateControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);

        $controllerManager = $this->prophesize(ControllerManager::class);
        $controllerManager->getServiceLocator()->willReturn($serviceLocator);

        $generator = $this->prophesize(Generator::class);
        $serviceLocator->get(Generator::class)->willReturn($generator->reveal());

        $factory = new GenerateControllerFactory();

        $controller = $factory->createService($controllerManager->reveal());

        $this->assertTrue($controller instanceof GenerateController);
    }
}
