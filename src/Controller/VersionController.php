<?php

namespace T4web\Migrations\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;
use T4web\Migrations\Version\Table;
use T4web\Migrations\Exception\RuntimeException;

/**
 * Migration commands controller
 */
class VersionController extends AbstractActionController
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function onDispatch(MvcEvent $e)
    {
        if (!$e->getRequest() instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        $response = sprintf("Current version %s\n", $this->table->getCurrentVersion());

        $e->setResult($response);

        return $response;
    }
}
