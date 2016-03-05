<?php

namespace T4web\MigrationsTest\Controller;

use Prophecy\Argument;
use T4web\Migrations\Controller\ApplyController;
use T4web\Migrations\Service\Migration;
use T4web\Migrations\Exception\RuntimeException;

class ApplyControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnDispatchNotConsoleRequest()
    {
        $migration = $this->prophesize(Migration::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');

        $controller = new ApplyController($migration->reveal());

        $event->getRequest()->willReturn(
            $this->prophesize('Zend\Http\Request')->reveal()
        );

        $this->setExpectedException(RuntimeException::class);

        $controller->onDispatch($event->reveal());
    }

    public function testOnDispatchForce()
    {
        $migration = $this->prophesize(Migration::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $request = $this->prophesize('Zend\Console\Request');

        $controller = new ApplyController($migration->reveal());

        $event->getRequest()->willReturn(
            $request->reveal()
        );

        $request->getParam('version')->willReturn(null);
        $request->getParam('force')->willReturn(true);
        $request->getParam('down')->willReturn(false);

        $event->setResult("Can't force migration apply without migration version explicitly set.\n")
            ->willReturn(null);

        $response = $controller->onDispatch($event->reveal());

        $this->assertEquals(
            "Can't force migration apply without migration version explicitly set.\n",
            $response
        );
    }

    public function testOnDispatch()
    {
        $migration = $this->prophesize(Migration::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $request = $this->prophesize('Zend\Console\Request');

        $controller = new ApplyController($migration->reveal());

        $event->getRequest()->willReturn(
            $request->reveal()
        );

        $request->getParam('version')->willReturn(null);
        $request->getParam('force')->willReturn(false);
        $request->getParam('down')->willReturn(false);

        $migration->migrate(null, false, false)->willReturn(null);
        $event->setResult("Migrations applied!\n")->willReturn(null);

        $response = $controller->onDispatch($event->reveal());

        $this->assertEquals("Migrations applied!\n", $response);
    }
}
