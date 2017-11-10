<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;

class NearestNeighbourReader implements ReaderInterface
{
    /**
     * @var MappingInterface
     */
    private $mapping;
    /**
     * @var TileInterface[][][]
     */
    private $tiles;
    /**
     * @var float
     */
    private $rowSize;
    /**
     * @var float
     */
    private $columnSize;
    /**
     * @var int
     */
    private $tileHeight;
    /**
     * @var int
     */
    private $tileWidth;
    /**
     * @var int
     */
    private $maxColumn;
    /**
     * @var int
     */
    private $maxRow;

    /**
     * @param SpecificationInterface $specification
     * @param MappingInterface $mapping
     * @param TileInterface[][][] $tiles
     */
    public function __construct(
        SpecificationInterface $specification,
        MappingInterface $mapping,
        array $tiles
    ) {
        $this->mapping = $mapping;
        $this->tiles = $tiles;
        $this->maxColumn = $specification->getLayout()->getWidth() - 1;
        $this->maxRow = $specification->getLayout()->getHeight() - 1;
        $this->columnSize = 1 / $specification->getLayout()->getWidth();
        $this->rowSize = 1 / $specification->getLayout()->getHeight();
        $this->tileWidth = $specification->getTileSize()->getWidth();
        $this->tileHeight = $specification->getTileSize()->getHeight();
    }

    /**
     * @inheritDoc
     */
    public function getColorAt($latitude, $longitude)
    {
        $position = $this->mapping->getPosition($latitude, $longitude);
        $u = $position[1] / $this->rowSize;
        $v = $position[2] / $this->columnSize;
        $column = min((int) $u, $this->maxColumn);
        $row = min((int) $v, $this->maxRow);
        $tileU = $u - $column;
        $tileV = $v - $row;
        $x = (int) min(0.5 + ($tileU * $this->tileWidth), $this->tileWidth - 1);
        $y = (int) min(0.5 + ($tileV * $this->tileHeight), $this->tileHeight - 1);
        return $this
            ->tiles[$position[0]][$column][$row]
            ->getImage()
            ->getColorAt($x, $y);
    }
}
