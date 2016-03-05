<?php

namespace T4web\MigrationsTest\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Migrations\MigrationVersion\Table;
use T4web\Migrations\Service\VersionResolver;
use T4web\Migrations\Service\VersionResolverFactory;
use T4web\Migrations\Config;

class VersionResolverFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $config = $this->prophesize(Config::class);
        $table = $this->prophesize(Table::class);

        $serviceLocator->get(Config::class)->willReturn($config->reveal());
        $serviceLocator->get(Table::class)->willReturn($table->reveal());

        $factory = new VersionResolverFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertTrue($service instanceof VersionResolver);
    }
}
