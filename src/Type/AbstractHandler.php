<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Type\ValidatingMappingInterface;
use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\Image\Manager;
use AmaTeam\Image\Projection\Tile\Loader;
use AmaTeam\Image\Projection\Tile\Tile;
use AmaTeam\Image\Projection\API\Type\HandlerInterface;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;
use BadMethodCallException;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param FilesystemInterface $filesystem
     * @param Manager $imageManager
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        FilesystemInterface $filesystem,
        Manager $imageManager,
        LoggerInterface $logger = null
    ) {
        $this->filesystem = $filesystem;
        $this->imageManager = $imageManager;
        $this->logger = $logger ?: new NullLogger();
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
        if (!$tileSize) {
            throw new ValidationException('Could not compute tile size');
        }
        $size = $specification->getSize() ?: SizeExtractor::extractSize($face);
        $mapping = $this->createMapping($size);
        if ($mapping instanceof ValidatingMappingInterface) {
            $mapping->validate($tree, $specification);
        }
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
        $context = ['target' => $target,];
        $this->logger->debug('Creating tile generator for {target}', $context);
        return new DefaultGenerator(
            $this->imageManager,
            new GenerationDetails($source, $mapping, $target, $filters),
            $this->logger
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
