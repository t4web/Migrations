<?php

namespace T4web\Migrations\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;
use T4web\Migrations\Service\Migration;
use T4web\Migrations\Exception\RuntimeException;

/**
 * Migration commands controller
 */
class VersionController extends AbstractActionController
{
    /**
     * @var Migration
     */
    protected $migration;

    /**
     * MigrateController constructor.
     *
     * @param Migration $migration
     */
    public function __construct(Migration $migration)
    {
        $this->migration = $migration;
    }

    public function onDispatch(MvcEvent $e)
    {
        if (!$e->getRequest() instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        $response = sprintf("Current version %s\n", $this->migration->getCurrentVersion());

        $e->setResult($response);

        return $response;
    }
}
