<?php

namespace T4web\MigrationsTest\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Migrations\Version\Table;
use T4web\Migrations\Version\Resolver;
use T4web\Migrations\Service\Migration;
use T4web\Migrations\Service\MigrationFactory;

class MigrationFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $table = $this->prophesize(Table::class);
        $resolver = $this->prophesize(Resolver::class);

        $serviceLocator->get(Table::class)->willReturn($table->reveal());
        $serviceLocator->get(Resolver::class)->willReturn($resolver->reveal());
        $serviceLocator->get('console')
            ->willReturn($this->prophesize('Zend\Console\Adapter\Posix'));

        $factory = new MigrationFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertTrue($service instanceof Migration);
    }
}
