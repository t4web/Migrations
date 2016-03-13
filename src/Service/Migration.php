<?php

namespace T4web\Migrations\Service;

use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\Sql\Ddl;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Console\Adapter\Posix as Console;
use T4web\Migrations\Migration\AbstractMigration;
use T4web\Migrations\Version\Table;
use T4web\Migrations\Version\Resolver;

/**
 * Main migration logic
 */
class Migration
{
    /**
     * @var Table
     */
    protected $versionTable;

    /**
     * @var Console
     */
    protected $console;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param Table                    $versionTable
     * @param Resolver                 $versionResolver
     * @param Console                  $console
     * @param ServiceLocatorInterface  $serviceLocator
     * @throws \Exception
     */
    public function __construct(
        Resolver $versionResolver,
        Table $versionTable,
        Console $console,
        ServiceLocatorInterface $serviceLocator = null
    ) {
        $this->versionResolver = $versionResolver;
        $this->versionTable = $versionTable;
        $this->console = $console;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param int $version target migration version, if not set all not applied available migrations will be applied
     * @param bool $force force apply migration
     * @param bool $down rollback migration
     * @throws \Exception
     */
    public function migrate($version = null, $force = false, $down = false)
    {
        $migrations = $this->versionResolver->getAll($force);

        if (!is_null($version) && !$this->hasMigrationVersions($migrations, $version)) {
            throw new \Exception(sprintf('Migration version %s is not found!', $version));
        }

        $currentMigrationVersion = $this->versionTable->getCurrentVersion();
        if (!is_null($version) && $version == $currentMigrationVersion && !$force) {
            throw new \Exception(sprintf('Migration version %s is current version!', $version));
        }

        if ($version && $force) {
            foreach ($migrations as $migration) {
                if ($migration['version'] == $version) {
                    // if existing migration is forced to apply - delete its information from migrated
                    // to avoid duplicate key error
                    if (!$down) {
                        $this->versionTable->delete($migration['version']);
                    }
                    $this->applyMigration($migration, $down);
                    break;
                }
            }

            return;
        }

        foreach ($this->versionResolver->getAll() as $migration) {
            $this->applyMigration($migration, false);
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

    protected function applyMigration(array $migration, $down = false)
    {
        try {
            /** @var $migrationObject AbstractMigration */
            $migrationObject = new $migration['class']($this->serviceLocator);

            $this->console->writeLine(
                sprintf(
                    "%s Execute migration class %s.",
                    $migration['class'],
                    $down ? 'down' : 'up'
                )
            );

            if ($down) {
                $migrationObject->down();
                $this->versionTable->delete($migration['version']);
            } else {
                $migrationObject->up();
                $this->versionTable->save($migration['version']);
            }
        } catch (InvalidQueryException $e) {
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
            $msg = sprintf('%s; File: %s; Line #%d', $e->getMessage(), $e->getFile(), $e->getLine());
            throw new \Exception($msg, $e->getCode(), $e);
        }
    }
}
