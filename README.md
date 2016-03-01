# Migrations

Simple Migrations for Zend Framework 2.

## Supported Drivers
The following DB adapter drivers are supported by this module.

  * Pdo_Sqlite
  * Pdo_Mysql
  * Mysqli _only if you configure the driver options with `'buffer_results' => true`_


## Installation

### Using composer

```sh
php composer.phar require t4web/migrations
```

or add to your composer.json
```json
"require": {
  "t4web/migrations": "^1.0.0"
}
```

Then add `T4web\Migrations` to the `modules` array in application.config.php


## Configuration

### Add to your configuration:

```php
'migrations' => array(
    'dir' => dirname(__FILE__) . '/../../../migrations',
    'namespace' => 'T4web\Migrations',    
    'adapter' => 'Zend\Db\Adapter\Adapter',
    'show_log' => true
),
```
  
### General options: 

The top-level key used to configure this module is `migrations`. 

#### Migration Configurations: Migrations

Each key under `migrations` is a migrations configuration.

##### Sub-key: `dir`

The path to the directory where migration files are stored. Defaults to `./migrations` in the project root dir.

##### Sub-key: `namespace` 

The class namespace that migration classes will be generated with. Defaults to `T4web\Migrations`.

##### Sub-key: `show_log` (optional)

Flag to log output of the migration. Defaults to `true`.

##### Sub-key: `adapter` (optional)

The service alias that will be used to fetch a `Zend\Db\Adapter\Adapter` from the service manager.


## Usage

### Available commands

* `migration version` - show last applied migration (`name` specifies a configured migration)
* `migration list [--all]` - list available migrations (`all` includes applied migrations)
* `migration apply [<version>] [--force] [--down] [--fake]` - apply or rollback migration
* `migration generate` - generate migration class

Migration classes are stored in `/path/to/project/migrations/` dir by default.

Generic migration class has name `Version_<YmdHis>` and implement `T4web\Migrations\Migration\MigrationInterface`.

### Migration class example

```php
<?php

namespace T4web\Migrations;

use `T4web\Migrations\Migration\AbstractMigration;
use Zend\Db\Metadata\MetadataInterface;

class Version_20130403165433 extends AbstractMigration
{
    public static $description = "Migration description";

    public function up(MetadataInterface $schema)
    {
        //$this->addSql(/*Sql instruction*/);
    }

    public function down(MetadataInterface $schema)
    {
        //$this->addSql(/*Sql instruction*/);
    }
}
```

#### Multi-statement sql
While this module supports execution of multiple SQL statements it does not have way to detect if any other statement than the first contained an error. It is *highly* recommended you only provide single SQL statements to `addSql` at a time.
I.e instead of

```
$this->addSql('SELECT NOW(); SELECT NOW(); SELECT NOW();');
```

You should use

```
$this->addSql('SELECT NOW();');
$this->addSql('SELECT NOW();');
$this->addSql('SELECT NOW();');
```

### Accessing ServiceLocator In Migration Class

By implementing the `Zend\ServiceManager\ServiceLocatorAwareInterface` in your migration class you get access to the
ServiceLocator used in the application.

```php
<?php

namespace T4web\Migrations;

use T4web\Migrations\Migration\AbstractMigration;
use Zend\Db\Metadata\MetadataInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Version_20130403165433 extends AbstractMigration
                            implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public static $description = "Migration description";

    public function up(MetadataInterface $schema)
    {
         //$this->getServiceLocator()->get(/*Get service by alias*/);
         //$this->addSql(/*Sql instruction*/);

    }

    public function down(MetadataInterface $schema)
    {
        //$this->getServiceLocator()->get(/*Get service by alias*/);
        //$this->addSql(/*Sql instruction*/);
    }
}
```

### Accessing Zend Db Adapter In Migration Class

By implementing the `Zend\Db\Adapter\AdapterAwareInterface` in your migration class you get access to the
Db Adapter configured for the migration.

```php
<?php

namespace T4web\Migrations;

use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\Sql\Ddl\Column\Integer;
use Zend\Db\Sql\Ddl\Column\Varchar;
use Zend\Db\Sql\Ddl\Constraint\PrimaryKey;
use Zend\Db\Sql\Ddl\CreateTable;
use Zend\Db\Sql\Ddl\DropTable;
use T4web\Migrations\Migration\AbstractMigration;
use Zend\Db\Metadata\MetadataInterface;

class Version_20150524162247 extends AbstractMigration implements AdapterAwareInterface
{
    use AdapterAwareTrait;

    public static $description = "Migration description";

    public function up(MetadataInterface $schema)
    {
        $table = new CreateTable('my_table');
        $table->addColumn(new Integer('id', false));
        $table->addConstraint(new PrimaryKey('id'));
        $table->addColumn(new Varchar('my_column', 64));
        $this->addSql($table->getSqlString($this->adapter->getPlatform()));
    }

    public function down(MetadataInterface $schema)
    {
        $drop = new DropTable('my_table');
        $this->addSql($drop->getSqlString($this->adapter->getPlatform()));
    }
}
```
