<?php

namespace T4web\MigrationsTest\Service;

use Prophecy\Argument;
use T4web\Filesystem\Filesystem;
use T4web\Migrations\Config;
use T4web\Migrations\Service\Generator;
use T4web\Migrations\Exception\RuntimeException;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Generator
     */
    private $generator;

    private $filesystem;

    public function setUp()
    {
        $config = $this->prophesize(Config::class);
        $this->filesystem = $this->prophesize(Filesystem::class);

        $config->getDir()->willReturn('/migrations');
        $config->getNamespace()->willReturn('T4web\Migrations');

        $this->generator = new Generator($config->reveal(), $this->filesystem->reveal());
    }

    public function testGenerateVersionExists()
    {
        $this->filesystem->exists(Argument::type('string'))->willReturn(true);

        $this->setExpectedException(RuntimeException::class);

        $this->generator->generate();
    }

    public function testGenerate()
    {
        $this->filesystem->exists(Argument::type('string'))->willReturn(false);
        $this->filesystem->put(Argument::type('string'), Argument::type('string'))->willReturn(null);

        $classPath = $this->generator->generate();

        $this->assertContains('Version_', $classPath);
    }
}
