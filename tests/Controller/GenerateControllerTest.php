<?php

namespace T4web\MigrationsTest\Controller;

use Prophecy\Argument;
use T4web\Migrations\Controller\GenerateController;
use T4web\Migrations\Service\Generator;
use T4web\Migrations\Exception\RuntimeException;

class GenerateControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnDispatchNotConsoleRequest()
    {
        $generator = $this->prophesize(Generator::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');

        $controller = new GenerateController($generator->reveal());

        $event->getRequest()->willReturn(
            $this->prophesize('Zend\Http\Request')->reveal()
        );

        $this->setExpectedException(RuntimeException::class);

        $controller->onDispatch($event->reveal());
    }

    public function testOnDispatch()
    {
        $generator = $this->prophesize(Generator::class);
        $event = $this->prophesize('Zend\Mvc\MvcEvent');

        $controller = new GenerateController($generator->reveal());

        $event->getRequest()->willReturn(
            $this->prophesize('Zend\Console\Request')->reveal()
        );

        $classPath = __FILE__;

        $generator->generate()->willReturn($classPath);
        $event->setResult("Generated skeleton class @ $classPath\n")->willReturn(null);

        $response = $controller->onDispatch($event->reveal());

        $this->assertEquals("Generated skeleton class @ $classPath\n", $response);
    }
}
