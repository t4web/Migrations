<?php
namespace T4web\Migrations\Service;

use ArrayIterator;
use GlobIterator;
use FilesystemIterator;
use ReflectionClass;
use ReflectionProperty;
use T4web\Migrations\Config;
use T4web\Migrations\MigrationVersion\Table;
use T4web\Migrations\Migration\MigrationInterface;

class VersionResolver
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Table
     */
    private $versionTable;

    public function __construct(Config $config, $versionTable)
    {
        $this->config = $config;
        $this->versionTable = $versionTable;
    }

    public function getAll($all = false)
    {
        $classes = new ArrayIterator();

        $iterator = new GlobIterator(
            sprintf('%s/Version_*.php', $this->config->getDir()),
            FilesystemIterator::KEY_AS_FILENAME
        );
        foreach ($iterator as $item) {
            /** @var $item \SplFileInfo */
            if (preg_match('/(Version_(\d+))\.php/', $item->getFilename(), $matches)) {
                $applied = $this->versionTable->applied($matches[2]);
                if ($all || !$applied) {
                    $className = $this->config->getNamespace() . '\\' . $matches[1];

                    if (!class_exists($className)) { /** @noinspection PhpIncludeInspection */
                        require_once $this->config->getDir() . '/' . $item->getFilename();
                    }

                    if (class_exists($className)) {
                        $reflectionClass = new ReflectionClass($className);
                        $reflectionDescription = new ReflectionProperty($className, 'description');

                        if ($reflectionClass->implementsInterface(MigrationInterface::class)) {
                            $classes->append(
                                [
                                    'version' => $matches[2],
                                    'class' => $className,
                                    'description' => $reflectionDescription->getValue(),
                                    'applied' => $applied,
                                ]
                            );
                        }
                    }
                }
            }
        }

        $classes->uasort(
            function ($a, $b) {
                if ($a['version'] == $b['version']) {
                    return 0;
                }

                return ($a['version'] < $b['version']) ? -1 : 1;
            }
        );

        return $classes;
    }
}
