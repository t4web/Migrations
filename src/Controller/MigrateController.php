<?php

namespace T4web\Migrations\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use T4web\Migrations\Service\Migration;
use T4web\Migrations\Service\Generator;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;

/**
 * Migration commands controller
 */
class MigrateController extends AbstractActionController
{
    /**
     * @var Migration
     */
    protected $migration;

    /**
     * @var Generator
     */
    protected $generator;

    public function onDispatch(MvcEvent $e)
    {
        if (!$this->getRequest() instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        parent::onDispatch($e);
    }
    /**
     * MigrateController constructor.
     *
     * @param Migration $migration
     * @param Generator $generator
     */
    public function __construct(Migration $migration, Generator $generator)
    {
        $this->migration = $migration;
        $this->generator = $generator;
    }

    /**
     * List migrations - not applied by default, all with 'all' flag.
     *
     * @return string
     */
    public function listAction()
    {
        $migrations = $this->getMigration()->getMigrationClasses($this->getRequest()->getParam('all'));
        $list = [];
        foreach ($migrations as $m) {
            $list[] = sprintf("%s %s - %s", $m['applied'] ? '-' : '+', $m['version'], $m['description']);
        }
        return (empty($list) ? 'No migrations available.' : implode("\n", $list)) . "\n";
    }

    /**
     * Apply migration
     */
    public function applyAction()
    {
        $version = $this->getRequest()->getParam('version');
        $force = $this->getRequest()->getParam('force');
        $down = $this->getRequest()->getParam('down');
        $fake = $this->getRequest()->getParam('fake');

        if (is_null($version) && $force) {
            return "Can't force migration apply without migration version explicitly set.\n";
        }
        if (is_null($version) && $fake) {
            return "Can't fake migration apply without migration version explicitly set.\n";
        }

        $this->getMigration()->migrate($version, $force, $down, $fake);
        return "Migrations applied!\n";
    }

    /**
     * Generate new migration skeleton class
     */
    public function generateAction()
    {
        $classPath = $this->getGenerator()->generate();

        return sprintf("Generated skeleton class @ %s\n", realpath($classPath));
    }

    /**
     * @return Migration
     */
    public function getMigration()
    {
        return $this->migration;
    }

    /**
     * @return Generator
     */
    public function getGenerator()
    {
        return $this->generator;
    }
}
