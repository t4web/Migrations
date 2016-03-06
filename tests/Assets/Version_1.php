<?php

namespace T4web\MigrationsTest\Assets;

use T4web\Migrations\Migration\AbstractMigration;

class Version_1 extends AbstractMigration
{
    public static $description = "Some migration 1";

    public function up()
    {
        //$this->addSql("SELECT *");
    }

    public function down()
    {
        //$this->addSql("DELETE *");
    }
}
