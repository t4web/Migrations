<?php

namespace T4web\MigrationsTest\Service;

use Prophecy\Argument;
use Zend\Db\TableGateway\TableGateway;
use T4web\Migrations\Version\Table;

class TableTest extends \PHPUnit_Framework_TestCase
{
    private $tableGataway;

    /**
     * @var Table
     */
    private $table;

    public function setUp()
    {
        $this->tableGataway = $this->prophesize(TableGateway::class);

        $this->table = new Table($this->tableGataway->reveal());
    }

    public function testSave()
    {
        $version = '1';
        $this->tableGataway->insert(['version' => $version])->willReturn(null);
        $this->tableGataway->getLastInsertValue()->willReturn(3);

        $lastInsertedId = $this->table->save($version);

        $this->assertEquals(3, $lastInsertedId);
    }

    public function testDelete()
    {
        $version = '1';
        $this->tableGataway->delete(['version' => $version])->willReturn(null);

        $this->table->delete($version);
    }

    public function testApplied()
    {
        $result = $this->prophesize('Zend\Db\ResultSet\ResultSet');

        $version = '1';
        $this->tableGataway->select(['version' => $version])->willReturn($result);
        $result->count()->willReturn(1);

        $result = $this->table->applied($version);

        $this->assertTrue($result);
    }

    public function testGetCurrentVersionNotExists()
    {
        $result = $this->prophesize('Zend\Db\ResultSet\ResultSet');

        $this->tableGataway->select(Argument::type('callable'))->willReturn($result);
        $result->count()->willReturn(false);

        $result = $this->table->getCurrentVersion();

        $this->assertEquals(0, $result);
    }

    public function testGetCurrentVersion()
    {
        $result = $this->prophesize('Zend\Db\ResultSet\ResultSet');
        $version = $this->prophesize('T4web\Migrations\Version\Version');

        $this->tableGataway->select(Argument::type('callable'))->willReturn($result);
        $result->count()->willReturn(1);
        $result->current()->willReturn($version->reveal());
        $version->getVersion()->willReturn(2);

        $result = $this->table->getCurrentVersion();

        $this->assertEquals(2, $result);
    }
}
