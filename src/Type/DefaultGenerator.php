<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\Conversion\FilterInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\PositionInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\Image\Manager;
use AmaTeam\Image\Projection\Tile\Position;
use AmaTeam\Image\Projection\Tile\Tile;
use AmaTeam\Image\Projection\API\Type\GeneratorInterface;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;
use Psr\Log\LoggerInterface;

class DefaultGenerator implements GeneratorInterface
{
    /**
     * @var Manager
     */
    private $imageManager;
    /**
     * @var ReaderInterface
     */
    private $reader;
    /**
     * @var MappingInterface
     */
    private $mapping;
    /**
     * @var SpecificationInterface
     */
    private $target;
    /**
     * @var FilterInterface[]
     */
    private $filters = [];


    /**
     * @var string[]
     */
    private $faces;
    /**
     * @var int
     */
    private $face = 0;
    /**
     * @var int
     */
    private $u = 0;
    /**
     * @var int
     */
    private $v = 0;


    /**
     * @var TileInterface
     */
    private $cursor;
    /**
     * @var PositionInterface
     */
    private $key;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Manager $imageManager
     * @param GenerationDetails $details
     * @param LoggerInterface $logger
     */
    public function __construct(
        Manager $imageManager,
        GenerationDetails $details,
        LoggerInterface $logger = null
    ) {
        $this->imageManager = $imageManager;
        $this->reader = $details->getSource();
        $this->mapping = $details->getMapping();
        $this->target = $details->getSpecification();
        $this->faces = $this->mapping->getFaces();
        $this->filters = $details->getFilters();
        $this->logger = $logger;
        $this->toFirstAllowedKey();
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        if (!$this->cursor) {
            $this->cursor = $this->generateTile($this->face, $this->u, $this->v);
        }
        return $this->cursor;
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->nextInternal();
        $this->toFirstAllowedKey();
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        if (!$this->key) {
            $this->key = new Position(
                $this->faces[$this->face],
                $this->u,
                $this->v
            );
        }
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->face < sizeof($this->faces);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->u = 0;
        $this->v = 0;
        $this->face = 0;
        $this->key = null;
        $this->cursor = null;
        $this->toFirstAllowedKey();
    }

    private function generateTile($faceIndex, $u, $v)
    {
        $specification = $this->target;
        $faceName = $this->faces[$faceIndex];
        $position = $this->key();
        $width = $specification->getTileSize()->getWidth();
        $height = $specification->getTileSize()->getHeight();
        $image = $this->imageManager->create($width, $height);
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $coordinates = $this
                    ->mapping
                    ->getCoordinates($faceName, $u * $width + $x, $v * $height + $y);
                $color = $this
                    ->reader
                    ->getColorAt($coordinates[0], $coordinates[1]);
                $image->setColorAt($x, $y, $color);
            }
        }
        return new Tile($position, $image);
    }

    private function allowed(
        PositionInterface $position,
        SpecificationInterface $specification
    ) {
        foreach ($this->filters as $filter) {
            if (!$filter->allows($position, $specification)) {
                return false;
            }
        }
        return true;
    }

    private function nextInternal()
    {
        $layout = $this->target->getLayout();
        $this->cursor = null;
        $this->key = null;
        $this->u++;
        if ($this->u >= $layout->getWidth()) {
            $this->u = 0;
            $this->v++;
        }
        if ($this->v >= $layout->getHeight()) {
            $this->v = 0;
            $this->face++;
        }
    }

    private function toFirstAllowedKey()
    {
        while ($this->valid() && !$this->allowed($this->key(), $this->target)) {
            $message = 'Current tile {position} is filtered, skipping';
            $this->logger->debug($message, ['position' => $this->key()]);
            $this->nextInternal();
        }
    }
}
