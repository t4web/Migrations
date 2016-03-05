<?php

namespace T4web\Migrations;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface;

class Module implements ConfigProviderInterface, AutoloaderProviderInterface, ConsoleUsageProviderInterface
{

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include dirname(__DIR__) . '/config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => dirname(__DIR__) . '/src',
                ],
            ],
        ];
    }

    /**
     * Returns an array or a string containing usage information for this module's Console commands.
     * The method is called with active Zend\Console\Adapter\AdapterInterface that can be used to directly access
     * Console and send output.
     *
     * If the result is a string it will be shown directly in the console window.
     * If the result is an array, its contents will be formatted to console window width. The array must
     * have the following format:
     *
     *     return array(
     *                'Usage information line that should be shown as-is',
     *                'Another line of usage info',
     *
     *                '--parameter'        =>   'A short description of that parameter',
     *                '-another-parameter' =>   'A short description of another parameter',
     *                ...
     *            )
     *
     * @param AdapterInterface $console
     * @return array|string|null
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            'Initialize migrations',
            'migration init' => 'Check config, create table `migrations`',

            'Get last applied migration version',
            'migration version [<name>]' => '',
            ['[<name>]', 'specify which configured migrations to run, defaults to `default`'],

            'List available migrations',
            'migration list [<name>] [--all]' => '',
            ['--all', 'Include applied migrations'],
            ['[<name>]', 'specify which configured migrations to run, defaults to `default`'],

            'Generate new migration skeleton class',
            'migration generate [<name>]' => '',
            ['[<name>]', 'specify which configured migrations to run, defaults to `default`'],

            'Execute migration',
            'migration apply [<name>] [<version>] [--force] [--down]' => '',
            ['[<name>]', 'specify which configured migrations to run, defaults to `default`'],
            [
                '--force',
                'Force apply migration even if it\'s older than the last migrated.'
                .' Works only with <version> explicitly set.'
            ],
            [
                '--down',
                'Force apply down migration. Works only with --force flag set.'
            ],
        ];
    }
}
