<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\Conversion\FilterInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\PositionInterface;
use AmaTeam\Image\Projection\Image\Manager;
use AmaTeam\Image\Projection\Tile\Position;
use AmaTeam\Image\Projection\Tile\Tile;
use AmaTeam\Image\Projection\API\Type\GeneratorInterface;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;

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
     * @var Tile
     */
    private $cursor;
    /**
     * @var PositionInterface
     */
    private $key;

    /**
     * @param Manager $imageManager
     * @param ReaderInterface $reader
     * @param MappingInterface $mapping
     * @param SpecificationInterface $target
     * @param FilterInterface[] $filters
     */
    public function __construct(
        Manager $imageManager,
        ReaderInterface $reader,
        SpecificationInterface $target,
        MappingInterface $mapping,
        array $filters = []
    ) {
        $this->imageManager = $imageManager;
        $this->reader = $reader;
        $this->mapping = $mapping;
        $this->target = $target;
        $this->faces = $mapping->getFaces();
        $this->filters = $filters;
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
        $parameters = $position->toPatternParameters();
        $path = $specification->getPattern()->resolve($parameters);
        $tile = new Tile((string) $path, $position, $this->imageManager);
        $width = $specification->getTileSize()->getWidth();
        $height = $specification->getTileSize()->getHeight();
        $image = $tile->createImage($width, $height);
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
        return $tile;
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
            $this->nextInternal();
        }
    }
}
