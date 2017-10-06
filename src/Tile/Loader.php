<?php

namespace AmaTeam\Image\Projection\Tile;

use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\Filesystem\Locator;
use AmaTeam\Image\Projection\Filesystem\Pattern;
use AmaTeam\Image\Projection\Image\Manager;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Manager $manager
     * @param FilesystemInterface $filesystem
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        Manager $manager,
        FilesystemInterface $filesystem,
        LoggerInterface $logger = null
    ) {
        $this->manager = $manager;
        $this->locator = new Locator($filesystem);
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @param Pattern $pattern
     * @return TileInterface[]
     */
    public function load(Pattern $pattern)
    {
        $context = ['pattern' => $pattern];
        $this->logger->debug('Loading tiles by pattern {pattern}', $context);
        $tiles = array_map(function ($entry) {
            return $this->createTile($entry['path'], $entry['parameters']);
        }, $this->locator->locate($pattern));
        $context = ['amount' => sizeof($tiles)];
        $this->logger->debug('Loaded {amount} tiles', $context);
        return $tiles;
    }

    /**
     * @param string $path
     * @param string[] $parameters
     * @return TileInterface
     */
    private function createTile($path, array $parameters)
    {
        $x = (int) self::lookup($parameters, ['x', 'h'], 0);
        $y = (int) self::lookup($parameters, ['y', 'v'], 0);
        $defaultFace = MappingInterface::DEFAULT_FACE;
        $face = self::lookup($parameters, ['face', 'f'], $defaultFace);
        $position = new Position($face, $x, $y);
        $context = ['position' => $position];
        $this->logger->debug('Loading tile {position}', $context);
        return new Tile($position, $this->manager->read($path));
    }

    /**
     * @param string[] $source
     * @param string[] $names
     * @param mixed $default
     * @return string|mixed
     */
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
