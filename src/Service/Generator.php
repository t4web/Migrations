<?php

namespace T4web\Migrations\Service;

use T4web\Filesystem\Filesystem;
use T4web\Migrations\Config;
use T4web\Migrations\Exception\RuntimeException;

/**
 * Migration class generator
 */
class Generator
{
    /**
     * @var string
     */
    protected $migrationsDir;

    /**
     * @var string
     */
    protected $migrationNamespace;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Config $config
     * @param Filesystem $filesystem
     * @throws \Exception
     */
    public function __construct(Config $config, Filesystem $filesystem)
    {
        $this->migrationsDir = $config->getDir();
        $this->migrationNamespace = $config->getNamespace();
        $this->filesystem = $filesystem;
    }

    /**
     * Generate new migration skeleton class
     *
     * @return string path to new skeleton class file
     * @throws \Exception
     */
    public function generate()
    {
        $className = 'Version_' . date('YmdHis', time());
        $classPath = $this->migrationsDir . DIRECTORY_SEPARATOR . $className . '.php';

        if ($this->filesystem->exists($classPath)) {
            throw new RuntimeException(sprintf('Migration %s exists!', $className));
        }
        $this->filesystem->put($classPath, $this->getTemplate($className));

        return $classPath;
    }

    /**
     * Get migration skeleton class raw text
     *
     * @param string $className
     * @return string
     */
    protected function getTemplate($className)
    {
        return sprintf(
            '<?php

namespace %s;

use T4web\Migrations\Migration\AbstractMigration;
use Zend\Db\Metadata\MetadataInterface;

class %s extends AbstractMigration
{
    public static $description = "Migration description";

    public function up(MetadataInterface $schema)
    {
        //$this->addSql(/*Sql instruction*/);
    }

    public function down(MetadataInterface $schema)
    {
        //throw new \RuntimeException(\'No way to go down!\');
        //$this->addSql(/*Sql instruction*/);
    }
}',
            $this->migrationNamespace,
            $className
        );

    }
}
