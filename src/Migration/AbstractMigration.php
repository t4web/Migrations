<?php

namespace T4web\Migrations\Migration;

use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractMigration implements MigrationInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}
