<?php

namespace T4web\Migrations\MigrationVersion;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class TableGatewayFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if($serviceLocator instanceof AbstractPluginManager)
        {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        $config = $serviceLocator->get('Config');
        $migrationConfig = $config['migrations'];

        $adapterName = isset($migrationConfig['adapter']) ? $migrationConfig['adapter'] : 'Zend\Db\Adapter\Adapter';
        /** @var $adapter \Zend\Db\Adapter\Adapter */
        $adapter = $serviceLocator->get($adapterName);

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new MigrationVersion());

        return new TableGateway(MigrationVersion::TABLE_NAME, $adapter, null, $resultSetPrototype);
    }
}
