<?php

namespace T4web\MigrationsTest\Migration;

use Prophecy\Argument;
use Zend\Db\Metadata\MetadataInterface;
use T4web\MigrationsTest\Assets\VersionX;
use T4web\Migrations\Service\OutputWriter;

class ApplyControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUpSql()
    {
        $metadata = $this->prophesize(MetadataInterface::class);
        $output = $this->prophesize(OutputWriter::class);

        $migration = new VersionX($metadata->reveal(), $output->reveal());

        $sql = $migration->getUpSql();

        $this->assertEquals(["SELECT *"], $sql);
    }

    public function testGetDownSql()
    {
        $metadata = $this->prophesize(MetadataInterface::class);
        $output = $this->prophesize(OutputWriter::class);

        $migration = new VersionX($metadata->reveal(), $output->reveal());

        $sql = $migration->getDownSql();

        $this->assertEquals(["DELETE *"], $sql);
    }
}
