<?php

namespace T4web\MigrationsTest\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use T4web\Migrations\Service\Migration;
use T4web\Migrations\Controller\VersionController;
use T4web\Migrations\Controller\VersionControllerFactory;

class VersionControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);

        $controllerManager = $this->prophesize(ControllerManager::class);
        $controllerManager->getServiceLocator()->willReturn($serviceLocator);

        $migration = $this->prophesize(Migration::class);
        $serviceLocator->get(Migration::class)->willReturn($migration->reveal());

        $factory = new VersionControllerFactory();

        $controller = $factory->createService($controllerManager->reveal());

        $this->assertTrue($controller instanceof VersionController);
    }
}
