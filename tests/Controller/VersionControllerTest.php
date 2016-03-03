<?php

namespace T4web\MigrationsTest\Controller;

use Prophecy\Argument;
use T4web\Migrations\Controller\VersionController;
use T4web\Migrations\Service\Migration;
use T4web\Migrations\Exception\RuntimeException;

class VersionControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnDispatchNotConsoleRequest()
    {
        $migration = $this->prophesize(Migration::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');

        $controller = new VersionController($migration->reveal());

        $event->getRequest()->willReturn(
            $this->prophesize('Zend\Http\Request')->reveal()
        );

        $this->setExpectedException(RuntimeException::class);

        $controller->onDispatch($event->reveal());
    }

    public function testOnDispatch()
    {
        $migration = $this->prophesize(Migration::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');

        $controller = new VersionController($migration->reveal());

        $event->getRequest()->willReturn(
            $this->prophesize('Zend\Console\Request')->reveal()
        );

        $migration->getCurrentVersion()->willReturn('XXX');
        $event->setResult("Current version XXX\n")->willReturn(null);

        $response = $controller->onDispatch($event->reveal());

        $this->assertEquals("Current version XXX\n", $response);
    }
}
