<?php

namespace T4web\Migrations\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\Db\Metadata\Metadata;

class MigrationFactory implements FactoryInterface
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

        $output = null;
        if (isset($migrationConfig['show_log']) && $migrationConfig['show_log']) {
            $console = $serviceLocator->get('console');
            $output = new OutputWriter(function ($message) use ($console) {
                $console->write($message . "\n");
            });
        }

        $migrationVersionTable = $serviceLocator->get('T4web\Migrations\MigrationVersion\Table');
        $metadata = new Metadata($adapter);

        return new Migration($adapter, $metadata, $migrationConfig, $migrationVersionTable, $output, $serviceLocator);
    }
}
