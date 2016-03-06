<?php

namespace T4web\MigrationsTest\Assets;

use T4web\Migrations\Migration\AbstractMigration;

class Version_3 extends AbstractMigration
{
    public static $description = "Some migration 3";

    public function up()
    {
        throw new \Exception('Any other exception');
    }

    public function down()
    {
        //$this->addSql("DELETE *");
    }
}
