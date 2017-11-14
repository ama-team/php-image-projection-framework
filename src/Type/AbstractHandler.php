<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Type\SourceOptionsInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\API\Type\TargetOptionsInterface;
use AmaTeam\Image\Projection\API\Type\ValidatingMappingInterface;
use AmaTeam\Image\Projection\Constants;
use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\Image\Manager;
use AmaTeam\Image\Projection\Specification;
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
    public function createReader(
        SpecificationInterface $specification,
        SourceOptionsInterface $options = null
    ) {
        $options = $options ?: new SourceOptions();
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
        $wrapper = $this->wrapSpecification($specification, $tileSize);
        $mapping = $this->getMapping();
        if ($mapping instanceof ValidatingMappingInterface) {
            $mapping->validate($tree, $wrapper);
        }
        return $this->instantiateReader($wrapper, $mapping, $tree, $options);
    }

    /**
     * @inheritDoc
     */
    public function createGenerator(
        ReaderInterface $source,
        SpecificationInterface $target,
        TargetOptionsInterface $options = null
    ) {
        if (!$target->getSize()) {
            $message = 'Provided specification doesn\'t contain it\'s size';
            throw new BadMethodCallException($message);
        }
        $options = $options ?: new TargetOptions();
        $mapping = $this->getMapping();
        $context = ['target' => $target,];
        $this->logger->debug('Creating tile generator for {target}', $context);
        return new DefaultGenerator(
            $this->imageManager,
            new GenerationDetails($source, $mapping, $target, $options->getFilters()),
            $this->logger
        );
    }

    /**
     * @param SpecificationInterface $specification
     * @param MappingInterface $mapping
     * @param TileInterface[][][] $tiles
     * @param SourceOptionsInterface $options
     *
     * @return ReaderInterface
     */
    protected function instantiateReader(
        SpecificationInterface $specification,
        MappingInterface $mapping,
        array $tiles,
        SourceOptionsInterface $options
    ) {
        $mode = $options->getInterpolationMode();
        if ($mode === Constants::INTERPOLATION_BILINEAR) {
            return new BilinearReader($specification, $mapping, $tiles);
        }
        return new NearestNeighbourReader($specification, $mapping, $tiles);
    }

    /**
     * @param SpecificationInterface $specification
     * @param Box $tileSize
     * @return SpecificationInterface
     */
    private function wrapSpecification(
        SpecificationInterface $specification,
        Box $tileSize
    ) {
        return new Specification(
            $specification->getType(),
            $specification->getPattern(),
            $tileSize,
            $specification->getLayout()
        );
    }

    /**
     * @return MappingInterface
     */
    abstract protected function getMapping();
}
