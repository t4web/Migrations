<?php

namespace T4web\MigrationsTest\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Filesystem\Filesystem;
use T4web\Migrations\Service\Generator;
use T4web\Migrations\Service\GeneratorFactory;
use T4web\Migrations\Config;

class GeneratorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $config = $this->prophesize(Config::class);
        $filesystem = $this->prophesize(Filesystem::class);

        $serviceLocator->get(Config::class)->willReturn($config->reveal());
        $serviceLocator->get(Filesystem::class)->willReturn($filesystem->reveal());

        $factory = new GeneratorFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertTrue($service instanceof Generator);
    }
}
