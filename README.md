[![Build Status](https://travis-ci.org/t4web/Migrations.svg?branch=master)](https://travis-ci.org/t4web/Migrations)
[![codecov.io](http://codecov.io/github/t4web/Migrations/coverage.svg?branch=master)](http://codecov.io/github/t4web/Migrations?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/t4web/Migrations/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/t4web/Migrations/?branch=master)

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
  "t4web/migrations": "^2.0.0"
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
* `migration apply [<version>] [--force] [--down]` - apply or rollback migration
* `migration generate` - generate migration class

Migration classes are stored in `/path/to/project/migrations/` dir by default.

Generic migration class has name `Version_<YmdHis>` and implement `T4web\Migrations\Migration\MigrationInterface`.

### Migration class example

```php
<?php

namespace T4web\Migrations;

use `T4web\Migrations\Migration\AbstractMigration;

class Version_20130403165433 extends AbstractMigration
{
    public static $description = "Migration description";

    public function up()
    {
        /** @var Zend\Db\ResultSet\ResultSet $result */
        //$result = $this->executeQuery(/*Sql instruction*/);
    }

    public function down()
    {
        //throw new \RuntimeException('No way to go down!');
        //$this->executeQuery(/*Sql instruction*/);
    }
}
```

### Accessing ServiceLocator In Migration Class

```php
<?php

namespace T4web\Migrations;

use T4web\Migrations\Migration\AbstractMigration;

class Version_20130403165433 extends AbstractMigration
{
    public static $description = "Migration description";

    public function up()
    {
         //$this->getServiceLocator()->get(/*Get service by alias*/);

    }

    public function down()
    {
        //$this->getServiceLocator()->get(/*Get service by alias*/);
    }
}
```
