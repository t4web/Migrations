<?php

namespace T4web\MigrationsTest\Assets;

use Zend\Db\Metadata\MetadataInterface;
use T4web\Migrations\Migration\AbstractMigration;

class Version_1 extends AbstractMigration
{
    public static $description = "Some migration 1";

    public function up(MetadataInterface $schema)
    {
        $this->addSql("SELECT *");
    }

    public function down(MetadataInterface $schema)
    {
        $this->addSql("DELETE *");
    }
}
