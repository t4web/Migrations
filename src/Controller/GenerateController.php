<?php

namespace T4web\Migrations\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\MvcEvent;
use T4web\Migrations\Service\Generator;
use T4web\Migrations\Exception\RuntimeException;

class GenerateController extends AbstractActionController
{
    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function onDispatch(MvcEvent $e)
    {
        if (!$e->getRequest() instanceof ConsoleRequest) {
            throw new RuntimeException('You can only use this action from a console!');
        }

        $classPath = $this->generator->generate();

        $response = sprintf("Generated skeleton class @ %s\n", realpath($classPath));

        $e->setResult($response);
        return $response;
    }
}
