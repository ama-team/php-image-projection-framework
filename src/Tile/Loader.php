<?php

namespace AmaTeam\Image\Projection\Tile;

use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\Filesystem\Locator;
use AmaTeam\Image\Projection\Filesystem\Pattern;
use AmaTeam\Image\Projection\Image\Manager;
use League\Flysystem\FilesystemInterface;

/**
 * Loads tiles from filesystem by specified pattern.
 */
class Loader
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var Locator
     */
    private $locator;

    /**
     * @param Manager $manager
     * @param FilesystemInterface $filesystem
     */
    public function __construct(
        Manager $manager,
        FilesystemInterface $filesystem
    ) {
        $this->manager = $manager;
        $this->locator = new Locator($filesystem);
    }

    /**
     * @param Pattern $pattern
     * @return Tile[]
     */
    public function load(Pattern $pattern)
    {
        return array_map(function ($entry) {
            return $this->createTile($entry['path'], $entry['parameters']);
        }, $this->locator->locate($pattern));
    }

    /**
     * @param string $path
     * @param string[] $parameters
     * @return Tile
     */
    private function createTile($path, array $parameters)
    {
        $x = (int) self::lookup($parameters, ['x', 'h'], 0);
        $y = (int) self::lookup($parameters, ['y', 'v'], 0);
        $defaultFace = MappingInterface::DEFAULT_FACE;
        $face = self::lookup($parameters, ['face', 'f'], $defaultFace);
        $position = new Position($face, $x, $y);
        return new Tile($path, $position, $this->manager);
    }

    private static function lookup(array $source, array $names, $default = null)
    {
        foreach ($names as $name) {
            if (isset($source[$name])) {
                return $source[$name];
            }
        }
        return $default;
    }
}
