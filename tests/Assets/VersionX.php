<?php

namespace T4web\MigrationsTest\Assets;

use T4web\Migrations\Migration\AbstractMigration;

class VersionX extends AbstractMigration
{
    public static $description = "Migration description";

    public function up()
    {
        //$this->addSql("SELECT *");
    }

    public function down()
    {
        //$this->addSql("DELETE *");
    }
}
