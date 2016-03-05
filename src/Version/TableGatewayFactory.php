<?php

namespace T4web\Migrations\Version;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use T4web\Migrations\Config;

class TableGatewayFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Config $config */
        $config = $serviceLocator->get(Config::class);

        $adapterName = $config->getAdapter();
        /** @var $adapter \Zend\Db\Adapter\Adapter */
        $adapter = $serviceLocator->get($adapterName);

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Version());

        return new TableGateway(Version::TABLE_NAME, $adapter, null, $resultSetPrototype);
    }
}
