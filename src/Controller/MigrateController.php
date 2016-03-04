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
        $this->generator = $generator;
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
     * @return Generator
     */
    public function getGenerator()
    {
        return $this->generator;
    }
}
