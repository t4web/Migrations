<?php

namespace T4web\Migrations\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;
use T4web\Migrations\Version\Resolver;
use T4web\Migrations\Exception\RuntimeException;

class ListController extends AbstractActionController
{
    /**
     * @var Resolver
     */
    protected $versionResolver;

    /**
     * @param Resolver $versionResolver
     */
    public function __construct(Resolver $versionResolver)
    {
        $this->versionResolver = $versionResolver;
    }

    public function onDispatch(MvcEvent $e)
    {
        if (!$e->getRequest() instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        $migrations = $this->versionResolver->getAll($e->getRequest()->getParam('all'));
        $list = [];
        foreach ($migrations as $m) {
            $list[] = sprintf("%s %s - %s", $m['applied'] ? '-' : '+', $m['version'], $m['description']);
        }

        $response = (empty($list) ? 'No migrations available.' : implode("\n", $list)) . "\n";

        $e->setResult($response);

        return $response;
    }
}
