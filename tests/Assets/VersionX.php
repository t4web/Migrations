<?php

namespace T4web\MigrationsTest\Assets;

use Zend\Db\Metadata\MetadataInterface;
use T4web\Migrations\Migration\AbstractMigration;

class VersionX extends AbstractMigration
{
    public static $description = "Migration description";

    public function up(MetadataInterface $schema)
    {
        $this->addSql("SELECT *");
    }

    public function down(MetadataInterface $schema)
    {
        $this->addSql("DELETE *");
    }
}
