<?php

namespace T4web\Migrations\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;
use T4web\Migrations\Service\Migration;
use T4web\Migrations\Exception\RuntimeException;

class ApplyController extends AbstractActionController
{
    /**
     * @var Migration
     */
    protected $migration;

    /**
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

        $version = $e->getRequest()->getParam('version');
        $force = $e->getRequest()->getParam('force');
        $down = $e->getRequest()->getParam('down');
        $fake = $e->getRequest()->getParam('fake');

        if (is_null($version) && $force) {
            $response = "Can't force migration apply without migration version explicitly set.\n";
            $e->setResult($response);
            return $response;
        }

        if (is_null($version) && $fake) {
            $response = "Can't fake migration apply without migration version explicitly set.\n";
            $e->setResult($response);
            return $response;
        }

        $this->migration->migrate($version, $force, $down, $fake);
        $response = "Migrations applied!\n";

        $e->setResult($response);
        return $response;
    }
}
