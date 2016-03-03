<?php

namespace T4web\MigrationsTest\Controller;

use Prophecy\Argument;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use T4web\Migrations\Service\Migration;
use T4web\Migrations\Service\Generator;
use T4web\Migrations\Controller\MigrateController;
use T4web\Migrations\Controller\MigrateControllerFactory;

class MigrateControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);

        $controllerManager = $this->prophesize(ControllerManager::class);
        $controllerManager->getServiceLocator()->willReturn($serviceLocator);

        $migration = $this->prophesize(Migration::class);
        $serviceLocator->get(Migration::class)->willReturn($migration);

        $generator = $this->prophesize(Generator::class);
        $serviceLocator->get(Generator::class)->willReturn($generator->reveal());

        $factory = new MigrateControllerFactory();

        $controller = $factory->createService($controllerManager->reveal());

        $this->assertTrue($controller instanceof MigrateController);
        $this->assertAttributeSame($migration->reveal(), 'migration', $controller);
        $this->assertAttributeSame($generator->reveal(), 'generator', $controller);
    }
}
