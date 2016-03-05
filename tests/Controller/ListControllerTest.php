<?php

namespace T4web\MigrationsTest\Controller;

use Prophecy\Argument;
use T4web\Migrations\Controller\ListController;
use T4web\Migrations\Service\VersionResolver;
use T4web\Migrations\Exception\RuntimeException;

class ListControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnDispatchNotConsoleRequest()
    {
        $resolver = $this->prophesize(VersionResolver::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');

        $controller = new ListController($resolver->reveal());

        $event->getRequest()->willReturn(
            $this->prophesize('Zend\Http\Request')->reveal()
        );

        $this->setExpectedException(RuntimeException::class);

        $controller->onDispatch($event->reveal());
    }

    public function testOnDispatch()
    {
        $resolver = $this->prophesize(VersionResolver::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $request = $this->prophesize('Zend\Console\Request');

        $controller = new ListController($resolver->reveal());

        $event->getRequest()->willReturn(
            $request->reveal()
        );

        $request->getParam('all')->willReturn(false);

        $migrations = [
            [
                'applied' => true,
                'version' => '123456789',
                'description' => 'Some migration',
            ],
        ];

        $resolver->getAll(false)->willReturn($migrations);

        $m = $migrations[0];

        $output = sprintf("%s %s - %s", $m['applied'] ? '-' : '+', $m['version'], $m['description']);

        $event->setResult("$output\n")->willReturn(null);

        $response = $controller->onDispatch($event->reveal());

        $this->assertEquals("$output\n", $response);
    }
}
