<?php

namespace T4web\MigrationsTest\Service;

use Prophecy\Argument;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Console\Adapter\Posix as Console;
use T4web\Migrations\Service\Migration;
use T4web\Migrations\Version\Table;
use T4web\Migrations\Version\Resolver;

class MigrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Migration
     */
    private $migration;

    private $resolver;
    private $table;
    private $console;

    public function setUp()
    {
        $this->resolver = $this->prophesize(Resolver::class);
        $this->table = $this->prophesize(Table::class);
        $this->console = $this->prophesize(Console::class);
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);

        $this->migration = new Migration(
            $this->resolver->reveal(),
            $this->table->reveal(),
            $this->console->reveal(),
            $serviceLocator->reveal()
        );
    }

    public function testMigrate()
    {
        $versions = [
            [
                'version' => 33,
                'class' => 'T4web\MigrationsTest\Assets\VersionX',
                'description' => 'Some description',
                'applied' => false,
            ],
            [
                'version' => 22,
                'class' => 'Some\Class_22',
                'description' => 'Some description',
                'applied' => true,
            ],
            [
                'version' => 77,
                'class' => 'T4web\MigrationsTest\Assets\Version_1',
                'description' => 'Some description',
                'applied' => false,
            ],
        ];

        $this->resolver->getAll(false)->willReturn($versions);
        $this->resolver->getAll()->willReturn([$versions[0], $versions[2]]);
        $this->table->getCurrentVersion()->willReturn(22);
        $this->table->save(33)->willReturn(null);
        $this->table->save(77)->willReturn(null);
        $this->console->writeLine(Argument::type('string'))->willReturn(null);

        $this->migration->migrate();
    }

    public function testMigrateWithInvalidQueryException()
    {
        $versions = [
            [
                'version' => 77,
                'class' => 'T4web\MigrationsTest\Assets\Version_2',
                'description' => 'Some description',
                'applied' => false,
            ],
        ];

        $this->resolver->getAll(false)->willReturn($versions);
        $this->resolver->getAll()->willReturn([$versions[0]]);
        $this->table->getCurrentVersion()->willReturn(22);

        $this->setExpectedException('\Exception');

        $this->migration->migrate();
    }

    public function testMigrateWithException()
    {
        $versions = [
            [
                'version' => 77,
                'class' => 'T4web\MigrationsTest\Assets\Version_3',
                'description' => 'Some description',
                'applied' => false,
            ],
        ];

        $this->resolver->getAll(false)->willReturn($versions);
        $this->resolver->getAll()->willReturn([$versions[0]]);
        $this->table->getCurrentVersion()->willReturn(22);

        $this->setExpectedException('\Exception');

        $this->migration->migrate();
    }

    public function testSortMigrationsByVersionDesc()
    {
        $versions = [
            [
                'version' => 33,
            ],
            [
                'version' => 22,
            ],
            [
                'version' => 77,
            ],
        ];

        $sortedVersion = $this->migration->sortMigrationsByVersionDesc(new \ArrayIterator($versions));

        $i = 0;
        $sort = [2, 0, 1];
        foreach ($sortedVersion->getArrayCopy() as $migration) {
            $this->assertEquals($versions[$sort[$i]], $migration);
            $i++;
        }
    }

    public function testHasMigrationVersions()
    {
        $versions = [
            [
                'version' => 33,
            ],
            [
                'version' => 77,
            ],
        ];

        $result = $this->migration->hasMigrationVersions(new \ArrayIterator($versions), 77);

        $this->assertTrue($result);

        $result = $this->migration->hasMigrationVersions(new \ArrayIterator($versions), 22);

        $this->assertFalse($result);
    }

    public function testGetMaxMigrationVersion()
    {
        $versions = [
            [
                'version' => 33,
            ],
            [
                'version' => 77,
            ],
        ];

        $result = $this->migration->getMaxMigrationVersion(new \ArrayIterator($versions));

        $this->assertEquals(77, $result);
    }
}
