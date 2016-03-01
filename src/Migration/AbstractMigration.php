<?php

namespace T4web\Migrations\Migration;

use Zend\Db\Metadata\MetadataInterface;
use T4web\Migrations\Service\OutputWriter;

abstract class AbstractMigration implements MigrationInterface
{

    /**
     * @var MetadataInterface
     */
    private $metadata;

    /**
     * @var OutputWriter
     */
    private $writer;

    private $sql = [];

    public function __construct(MetadataInterface $metadata, OutputWriter $writer)
    {
        $this->metadata = $metadata;
        $this->writer = $writer;
    }

    /**
     * Add migration query
     *
     * @param string $sql
     */
    protected function addSql($sql)
    {
        $this->sql[] = $sql;
    }

    /**
     * Get migration queries
     *
     * @return array
     */
    public function getUpSql()
    {
        $this->sql = [];
        $this->up($this->metadata);

        return $this->sql;
    }

    /**
     * Get migration rollback queries
     *
     * @return array
     */
    public function getDownSql()
    {
        $this->sql = [];
        $this->down($this->metadata);

        return $this->sql;
    }

    /**
     * @return OutputWriter
     */
    protected function getWriter()
    {
        return $this->writer;
    }
}
