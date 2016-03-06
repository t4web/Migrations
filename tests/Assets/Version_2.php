<?php

namespace T4web\MigrationsTest\Assets;

use Zend\Db\Adapter\Exception\InvalidQueryException;
use T4web\Migrations\Migration\AbstractMigration;

class Version_2 extends AbstractMigration
{
    public static $description = "Some migration 2";

    public function up()
    {
        throw new InvalidQueryException('Some SQL error');
    }

    public function down()
    {
        //$this->addSql("DELETE *");
    }
}
