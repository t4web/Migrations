<?php

namespace T4web\Migrations\Migration;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

abstract class AbstractMigration implements MigrationInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @var Adapter
     */
    private $dbAdapter;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function executeQuery($sql)
    {
        if (!$this->dbAdapter) {
            /** @var Adapter dbAdapter */
            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        }

        /** @var ResultSet $photos */
        return $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
    }
}
