<?php

namespace T4web\MigrationsTest\Version;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use T4web\Migrations\Version\TableGatewayFactory;
use T4web\Migrations\Config;

class TableGatewayFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $config = $this->prophesize(Config::class);
        $adapter = $this->prophesize('Zend\Db\Adapter\Adapter');

        $serviceLocator->get(Config::class)->willReturn($config->reveal());
        $config->getAdapter()->willReturn('Zend\Db\Adapter\Adapter');
        $serviceLocator->get('Zend\Db\Adapter\Adapter')->willReturn($adapter->reveal());

        $factory = new TableGatewayFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertTrue($service instanceof TableGateway);
    }
}
