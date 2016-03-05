<?php

namespace T4web\MigrationsTest\Service;

use Prophecy\Argument;
use T4web\Migrations\MigrationVersion\Table;
use T4web\Migrations\Config;
use T4web\Migrations\Service\VersionResolver;

class VersionResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Generator
     */
    private $generator;

    private $table;

    public function setUp()
    {
        $config = $this->prophesize(Config::class);
        $this->table = $this->prophesize(Table::class);

        $config->getDir()->willReturn(__DIR__ . '/../Assets');
        $config->getNamespace()->willReturn('T4web\MigrationsTest\Assets');

        $this->generator = new VersionResolver($config->reveal(), $this->table->reveal());
    }

    public function testGetAll()
    {
        $this->table->applied('1')->willReturn(false);

        $migrations = $this->generator->getAll();

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
