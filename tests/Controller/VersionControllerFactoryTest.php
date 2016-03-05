<?php

namespace T4web\MigrationsTest\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use T4web\Migrations\MigrationVersion\Table;
use T4web\Migrations\Controller\VersionController;
use T4web\Migrations\Controller\VersionControllerFactory;

class VersionControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);

        $controllerManager = $this->prophesize(ControllerManager::class);
        $controllerManager->getServiceLocator()->willReturn($serviceLocator);

        $table = $this->prophesize(Table::class);
        $serviceLocator->get(Table::class)->willReturn($table->reveal());

        $factory = new VersionControllerFactory();

        $controller = $factory->createService($controllerManager->reveal());

        $this->assertTrue($controller instanceof VersionController);
    }
}
