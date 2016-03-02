<?php

namespace T4web\Migrations\MigrationVersion;

class MigrationVersion
{

    const TABLE_NAME = 'migration_version';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $version;

    public function exchangeArray($data)
    {
        foreach (array_keys(get_object_vars($this)) as $property) {
            $this->{$property} = (isset($data[$property])) ? $data[$property] : null;
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
}
