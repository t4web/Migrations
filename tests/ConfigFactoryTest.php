<?php

namespace T4web\MigrationsTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use T4web\Filesystem\Filesystem;
use T4web\Migrations\Config;
use T4web\Migrations\ConfigFactory;

class ConfigFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $filesystem = $this->prophesize(Filesystem::class);

        $config = [
            'migrations' => [
                'dir' => '/../../../../migrations',
                'namespace' => 'T4web\Migrations',
            ],
        ];

        $serviceLocator->get('Config')->willReturn($config);
        $serviceLocator->get(Filesystem::class)->willReturn($filesystem);

        $factory = new ConfigFactory();

        $filesystem->isDir($config['migrations']['dir'])->willReturn(true);
        $filesystem->isWritable($config['migrations']['dir'])->willReturn(true);

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertInstanceOf(Config::class, $service);
    }
}
