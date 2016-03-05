<?php

namespace T4web\MigrationsTest\Version;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use T4web\Migrations\Version\Table;
use T4web\Migrations\Version\TableFactory;

class TableFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $tableGateway = $this->prophesize(TableGateway::class);

        $serviceLocator->get('T4web\Migrations\Version\TableGateway')
            ->willReturn($tableGateway->reveal());

        $factory = new TableFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertTrue($service instanceof Table);
    }
}
