<?php

namespace T4web\MigrationsTest\Controller;

use Prophecy\Argument;
use Zend\Db\Adapter\Adapter as DbAdapter;
use T4web\Migrations\Controller\InitController;
use T4web\Migrations\Exception\RuntimeException;

class InitControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnDispatchNotConsoleRequest()
    {
        $adapter = $this->prophesize('Zend\Db\Adapter\Adapter');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');

        $controller = new InitController($adapter->reveal());

        $event->getRequest()->willReturn(
            $this->prophesize('Zend\Http\Request')->reveal()
        );

        $this->setExpectedException(RuntimeException::class);

        $controller->onDispatch($event->reveal());
    }

    public function testOnDispatch()
    {
        $adapter = $this->prophesize('Zend\Db\Adapter\Adapter');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');

        $controller = new InitController($adapter->reveal());

        $event->getRequest()->willReturn(
            $this->prophesize('Zend\Console\Request')->reveal()
        );

        $platform = $this->prophesize('Zend\Db\Adapter\Platform\Mysql');
        $adapter->getPlatform()->willReturn($platform->reveal());
        $adapter->query(Argument::type('string'), DbAdapter::QUERY_MODE_EXECUTE)->willReturn(null);

        $controller->onDispatch($event->reveal());
    }
}
