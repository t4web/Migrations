<?php

namespace T4web\MigrationsTest\Controller;

use Prophecy\Argument;
use T4web\Migrations\Controller\VersionController;
use T4web\Migrations\Version\Table;
use T4web\Migrations\Exception\RuntimeException;

class VersionControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnDispatchNotConsoleRequest()
    {
        $table = $this->prophesize(Table::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');

        $controller = new VersionController($table->reveal());

        $event->getRequest()->willReturn(
            $this->prophesize('Zend\Http\Request')->reveal()
        );

        $this->setExpectedException(RuntimeException::class);

        $controller->onDispatch($event->reveal());
    }

    public function testOnDispatch()
    {
        $table = $this->prophesize(Table::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');

        $controller = new VersionController($table->reveal());

        $event->getRequest()->willReturn(
            $this->prophesize('Zend\Console\Request')->reveal()
        );

        $table->getCurrentVersion()->willReturn('XXX');
        $event->setResult("Current version XXX\n")->willReturn(null);

        $response = $controller->onDispatch($event->reveal());

        $this->assertEquals("Current version XXX\n", $response);
    }
}
