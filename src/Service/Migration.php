<?php

namespace T4web\Migrations\Service;

use T4web\Migrations\Migration\AbstractMigration;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\Sql\Ddl;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Main migration logic
 */
class Migration
{
    protected $migrationsDir;
    protected $migrationsNamespace;
    protected $adapter;
    /**
     * @var \Zend\Db\Adapter\Driver\ConnectionInterface
     */
    protected $connection;
    protected $metadata;
    protected $migrationVersionTable;
    protected $outputWriter;
    protected $serviceLocator;

    /**
     * @return OutputWriter
     */
    public function getOutputWriter()
    {
        return $this->outputWriter;
    }

    /**
     * @param \Zend\Db\Adapter\Adapter $adapter
     * @param                          $metadata
     * @param array                    $config
     * @param                          $migrationVersionTable
     * @param VersionResolver          $versionResolver
     * @param OutputWriter             $writer
     * @param null                     $serviceLocator
     * @throws \Exception
     */
    public function __construct(
        Adapter $adapter,
        $metadata,
        array $config,
        VersionResolver $versionResolver,
        $migrationVersionTable,
        OutputWriter $writer = null,
        $serviceLocator = null
    ) {
        $this->adapter = $adapter;
        $this->metadata = $metadata;
        $this->connection = $this->adapter->getDriver()->getConnection();
        $this->migrationsDir = $config['dir'];
        $this->migrationsNamespace = $config['namespace'];
        $this->versionResolver = $versionResolver;
        $this->migrationVersionTable = $migrationVersionTable;
        $this->outputWriter = $writer;
        $this->serviceLocator = $serviceLocator;

        if (is_null($this->migrationsDir)) {
            throw new \Exception('Migrations directory not set!');
        }

        if (is_null($this->migrationsNamespace)) {
            throw new \Exception('Unknown namespaces!');
        }

        if (!is_dir($this->migrationsDir)) {
            if (!mkdir($this->migrationsDir, 0775)) {
                throw new \Exception(sprintf('Failed to create migrations directory %s', $this->migrationsDir));
            }
        }
    }

    /**
     * @return int
     */
    public function getCurrentVersion()
    {
        return $this->migrationVersionTable->getCurrentVersion();
    }

    /**
     * @param int $version target migration version, if not set all not applied available migrations will be applied
     * @param bool $force force apply migration
     * @param bool $down rollback migration
     * @param bool $fake
     * @throws \Exception
     */
    public function migrate($version = null, $force = false, $down = false, $fake = false)
    {
        //$migrations = $this->getMigrationClasses($force);
        $migrations = $this->versionResolver->getAll($force);

        if (!is_null($version) && !$this->hasMigrationVersions($migrations, $version)) {
            throw new \Exception(sprintf('Migration version %s is not found!', $version));
        }

        $currentMigrationVersion = $this->migrationVersionTable->getCurrentVersion();
        if (!is_null($version) && $version == $currentMigrationVersion && !$force) {
            throw new \Exception(sprintf('Migration version %s is current version!', $version));
        }

        if ($version && $force) {
            foreach ($migrations as $migration) {
                if ($migration['version'] == $version) {
                    // if existing migration is forced to apply - delete its information from migrated
                    // to avoid duplicate key error
                    if (!$down) {
                        $this->migrationVersionTable->delete($migration['version']);
                    }
                    $this->applyMigration($migration, $down, $fake);
                    break;
                }
            }

            return;
        }

        foreach ($this->versionResolver->getAll() as $migration) {
            $this->applyMigration($migration, false, $fake);
        }
    }

    /**
     * @param \ArrayIterator $migrations
     * @return \ArrayIterator
     */
    public function sortMigrationsByVersionDesc(\ArrayIterator $migrations)
    {
        $sortedMigrations = clone $migrations;

        $sortedMigrations->uasort(
            function ($a, $b) {
                if ($a['version'] == $b['version']) {
                    return 0;
                }

                return ($a['version'] > $b['version']) ? -1 : 1;
            }
        );

        return $sortedMigrations;
    }

    /**
     * Check migrations classes existence
     *
     * @param \ArrayIterator $migrations
     * @param int            $version
     * @return bool
     */
    public function hasMigrationVersions(\ArrayIterator $migrations, $version)
    {
        foreach ($migrations as $migration) {
            if ($migration['version'] == $version) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ArrayIterator $migrations
     * @return int
     */
    public function getMaxMigrationVersion(\ArrayIterator $migrations)
    {
        $versions = [];
        foreach ($migrations as $migration) {
            $versions[] = $migration['version'];
        }

        sort($versions, SORT_NUMERIC);
        $versions = array_reverse($versions);

        return count($versions) > 0 ? $versions[0] : 0;
    }

    protected function applyMigration(array $migration, $down = false, $fake = false)
    {
        $this->connection->beginTransaction();

        try {
            /** @var $migrationObject AbstractMigration */
            $migrationObject = new $migration['class']($this->metadata, $this->outputWriter);

            if ($migrationObject instanceof ServiceLocatorAwareInterface) {
                if (is_null($this->serviceLocator)) {
                    throw new \RuntimeException(
                        sprintf(
                            'Migration class %s requires a ServiceLocator, but there is no instance available.',
                            get_class($migrationObject)
                        )
                    );
                }

                $migrationObject->setServiceLocator($this->serviceLocator);
            }

            if ($migrationObject instanceof AdapterAwareInterface) {
                if (is_null($this->adapter)) {
                    throw new \RuntimeException(
                        sprintf(
                            'Migration class %s requires an Adapter, but there is no instance available.',
                            get_class($migrationObject)
                        )
                    );
                }

                $migrationObject->setDbAdapter($this->adapter);
            }

            $this->outputWriter->writeLine(
                sprintf(
                    "%sExecute migration class %s %s",
                    $fake ? '[FAKE] ' : '',
                    $migration['class'],
                    $down ? 'down' : 'up'
                )
            );

            if (!$fake) {
                $sqlList = $down ? $migrationObject->getDownSql() : $migrationObject->getUpSql();
                foreach ($sqlList as $sql) {
                    $this->outputWriter->writeLine("Execute query:\n\n" . $sql);
                    $this->connection->execute($sql);
                }
            }

            if ($down) {
                $this->migrationVersionTable->delete($migration['version']);
            } else {
                $this->migrationVersionTable->save($migration['version']);
            }
            $this->connection->commit();
        } catch (InvalidQueryException $e) {
            $this->connection->rollback();
            $previousMessage = $e->getPrevious() ? $e->getPrevious()->getMessage() : null;
            $msg = sprintf(
                '%s: "%s"; File: %s; Line #%d',
                $e->getMessage(),
                $previousMessage,
                $e->getFile(),
                $e->getLine()
            );
            throw new \Exception($msg, $e->getCode(), $e);
        } catch (\Exception $e) {
            $this->connection->rollback();
            $msg = sprintf('%s; File: %s; Line #%d', $e->getMessage(), $e->getFile(), $e->getLine());
            throw new \Exception($msg, $e->getCode(), $e);
        }
    }
}
