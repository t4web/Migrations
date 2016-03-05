<?php

namespace T4web\MigrationsTest\Version;

use Prophecy\Argument;
use T4web\Migrations\Version\Table;
use T4web\Migrations\Config;
use T4web\Migrations\Version\Resolver;

class ResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Resolver
     */
    private $resolver;

    private $table;

    public function setUp()
    {
        $config = $this->prophesize(Config::class);
        $this->table = $this->prophesize(Table::class);

        $config->getDir()->willReturn(__DIR__ . '/../Assets');
        $config->getNamespace()->willReturn('T4web\MigrationsTest\Assets');

        $this->resolver = new Resolver($config->reveal(), $this->table->reveal());
    }

    public function testGetAll()
    {
        $this->table->applied('1')->willReturn(false);

        $migrations = $this->resolver->getAll();

        $this->assertEquals(
            [
                [
                    'version' => '1',
                    'class' => 'T4web\MigrationsTest\Assets\Version_1',
                    'description' => 'Some migration 1',
                    'applied' => false,
                ]
            ],
            $migrations->getArrayCopy());
    }
}
