<?php

namespace T4web\Migrations;

use T4web\Filesystem\Filesystem;
use T4web\Migrations\Exception\RuntimeException;

class Config
{
    /**
     * @var string
     */
    private $dir;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $adapter;

    /**
     * @var bool
     */
    private $showLog;

    public function __construct($appConfig, Filesystem $filesystem)
    {
        if (!isset($appConfig['migrations'])) {
            throw new RuntimeException("Missing migrations configuration");
        }

        $options = $appConfig['migrations'];

        if (!isset($options['dir'])) {
            throw new RuntimeException("`dir` has not be specified in migrations configuration");
        }

        $this->dir = $options['dir'];

        if (!isset($options['namespace'])) {
            throw new RuntimeException("`namespace` has not be specified in migrations configuration");
        }

        $this->namespace = $options['namespace'];

        if (!$filesystem->isDir($this->dir)) {
            $filesystem->mkdir($this->dir, 0775);
        } elseif (!$filesystem->isWritable($this->dir)) {
            throw new RuntimeException(sprintf('Migrations directory is not writable %s', $this->dir));
        }

        if (!isset($options['adapter'])) {
            $options['adapter'] = 'Zend\Db\Adapter\Adapter';
        }

        $this->adapter = $options['adapter'];

        if (isset($options['show_log'])) {
            $this->showLog = $options['show_log'];
        }
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @return bool
     */
    public function isShowLog()
    {
        return $this->showLog;
    }
}
