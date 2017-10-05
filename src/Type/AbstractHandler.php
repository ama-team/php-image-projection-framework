<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\Image\Manager;
use AmaTeam\Image\Projection\Tile\Loader;
use AmaTeam\Image\Projection\Tile\Tile;
use AmaTeam\Image\Projection\API\Type\HandlerInterface;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;
use BadMethodCallException;
use League\Flysystem\FilesystemInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var Manager
     */
    private $imageManager;
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @param FilesystemInterface $filesystem
     * @param Manager $imageManager
     */
    public function __construct(
        FilesystemInterface $filesystem,
        Manager $imageManager
    ) {
        $this->filesystem = $filesystem;
        $this->imageManager = $imageManager;
    }

    /**
     * @inheritDoc
     */
    public function read(SpecificationInterface $specification)
    {
        $loader = new Loader($this->imageManager, $this->filesystem);
        $pattern = $specification->getPattern();
        $tiles = $loader->load($pattern);
        if (empty($tiles)) {
            $message = "Couldn't find any tiles specified by pattern $pattern";
            throw new BadMethodCallException($message);
        }
        $tree = Tile::treeify($tiles);
        $face = reset($tree);
        $tileSize = $specification->getTileSize();
        $tileSize = $tileSize ?: SizeExtractor::extractTileSize($face);
        $size = $specification->getSize() ?: SizeExtractor::extractSize($face);
        $mapping = $this->createMapping($size);
        return $this->createReader($mapping, $tree, $tileSize);
    }

    /**
     * @inheritDoc
     */
    public function createGenerator(
        ReaderInterface $source,
        SpecificationInterface $target,
        array $filters = []
    ) {
        if (!$target->getSize()) {
            $message = 'Provided specification doesn\'t contain it\'s size';
            throw new BadMethodCallException($message);
        }
        $mapping = $this->createMapping($target->getSize());
        return new DefaultGenerator(
            $this->imageManager,
            $source,
            $target,
            $mapping,
            $filters
        );
    }

    /**
     * @param Box $size
     * @return MappingInterface
     */
    abstract protected function createMapping(Box $size);

    /**
     * @param MappingInterface $mapping
     * @param Tile[][][] $tiles
     * @param Box $tileSize
     * @return ReaderInterface
     */
    protected function createReader(
        MappingInterface $mapping,
        array $tiles,
        Box $tileSize
    ) {
        return new DefaultReader($mapping, $tiles, $tileSize);
    }
}
