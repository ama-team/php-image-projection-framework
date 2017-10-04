<?php

namespace AmaTeam\Image\Projection\Test\Support\Fixture\Projection;

use AmaTeam\Image\Projection\Test\Support\Structure;
use Symfony\Component\Yaml\Yaml;

class Manager
{
    /**
     * @var string
     */
    private $root;
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var Installer
     */
    private $installer;

    /**
     * @param string $root
     */
    public function __construct($root = null)
    {
        $root = $root ?: self::defaultWorkspace();
        $this->root = $root;
        $this->reader = new Reader($root);
        $this->installer = new Installer();
    }

    public function install()
    {
        return array_map([$this->installer, 'install'], $this->enumerate());
    }

    public function enumerate()
    {
        $path = implode(DIRECTORY_SEPARATOR, [
            Structure::getProjectRoot(),
            $this->root,
            Fixture::DEFINITIONS_FILE
        ]);
        $content = file_get_contents($path);
        $definitions = Yaml::parse($content);
        return $this->reader->readAll($definitions);
    }

    private static function defaultWorkspace()
    {
        return implode(DIRECTORY_SEPARATOR, ['tests', 'Data']);
    }
}
