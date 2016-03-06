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
        $this->table->applied('1')->willReturn(true);
        $this->table->applied('2')->willReturn(false);
        $this->table->applied('3')->willReturn(false);

        $migrations = $this->resolver->getAll();

        $this->assertEquals(
            [
                [
                    'version' => '2',
                    'class' => 'T4web\MigrationsTest\Assets\Version_2',
                    'description' => 'Some migration 2',
                    'applied' => false,
                ],
                [
                    'version' => '3',
                    'class' => 'T4web\MigrationsTest\Assets\Version_3',
                    'description' => 'Some migration 3',
                    'applied' => false,
                ]
            ],
            $migrations->getArrayCopy());
    }
}
