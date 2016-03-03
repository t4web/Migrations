<?php

namespace T4web\MigrationsTest\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use T4web\Migrations\Config;
use T4web\Migrations\Controller\InitController;
use T4web\Migrations\Controller\InitControllerFactory;

class InitControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);

        $controllerManager = $this->prophesize(ControllerManager::class);
        $controllerManager->getServiceLocator()->willReturn($serviceLocator);

        $config = $this->prophesize(Config::class);
        $serviceLocator->get(Config::class)->willReturn($config->reveal());

        $config->getAdapter()->willReturn('Zend\Db\Adapter\Adapter');

        $adapter = $this->prophesize('Zend\Db\Adapter\Adapter');
        $serviceLocator->get('Zend\Db\Adapter\Adapter')->willReturn($adapter->reveal());

        $factory = new InitControllerFactory();

        $controller = $factory->createService($controllerManager->reveal());

        $this->assertTrue($controller instanceof InitController);
    }
}
