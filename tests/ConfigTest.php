<?php

namespace T4web\MigrationsTest;

use T4web\Filesystem\Filesystem;
use T4web\Migrations\Config;
use T4web\Migrations\Exception\RuntimeException;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigWithoutMigrations()
    {
        $filesystem = $this->prophesize(Filesystem::class);

        $config = [];

        $this->setExpectedException(RuntimeException::class);

        $config = new Config($config, $filesystem->reveal());
    }

    public function testConfigWithoutDir()
    {
        $filesystem = $this->prophesize(Filesystem::class);

        $config = [
            'migrations' => [],
        ];

        $this->setExpectedException(RuntimeException::class);

        $config = new Config($config, $filesystem->reveal());
    }

    public function testConfigWithoutNamespace()
    {
        $filesystem = $this->prophesize(Filesystem::class);

        $config = [
            'migrations' => [],
        ];

        $this->setExpectedException(RuntimeException::class);

        $config = new Config($config, $filesystem->reveal());
    }

    public function testConfigWithoutDirCreation()
    {
        $filesystem = $this->prophesize(Filesystem::class);

        $config = [
            'migrations' => [
                'dir' => '/migrations',
                'namespace' => 'T4web\Migrations',
            ],
        ];

        $filesystem->isDir($config['migrations']['dir'])->willReturn(false);
        $filesystem->mkdir($config['migrations']['dir'], 0775)->willReturn(false);

        $this->setExpectedException(RuntimeException::class);

        $config = new Config($config, $filesystem->reveal());
    }

    public function testConfigWithDirNotWritable()
    {
        $filesystem = $this->prophesize(Filesystem::class);

        $config = [
            'migrations' => [
                'dir' => '/migrations',
                'namespace' => 'T4web\Migrations',
            ],
        ];

        $filesystem->isDir($config['migrations']['dir'])->willReturn(true);
        $filesystem->isWritable($config['migrations']['dir'])->willReturn(false);

        $this->setExpectedException(RuntimeException::class);

        $config = new Config($config, $filesystem->reveal());
    }

    public function testConfig()
    {
        $filesystem = $this->prophesize(Filesystem::class);

        $appConfig = [
            'migrations' => [
                'dir' => '/migrations',
                'namespace' => 'T4web\Migrations',
                'adapter' => 'Zend\Db\Adapter\Adapter',
                'show_log' => true
            ],
        ];

        $filesystem->isDir($appConfig['migrations']['dir'])->willReturn(true);
        $filesystem->isWritable($appConfig['migrations']['dir'])->willReturn(true);

        $config = new Config($appConfig, $filesystem->reveal());

        $this->assertEquals($appConfig['migrations']['dir'], $config->getDir());
        $this->assertEquals($appConfig['migrations']['namespace'], $config->getNamespace());
        $this->assertEquals($appConfig['migrations']['adapter'], $config->getAdapter());
        $this->assertEquals($appConfig['migrations']['show_log'], $config->isShowLog());
    }
}
