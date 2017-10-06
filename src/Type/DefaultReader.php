<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;

class DefaultReader implements ReaderInterface
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
     * @var int
     */
    private $tileHeight;
    /**
     * @var int
     */
    private $tileWidth;

    /**
     * @param MappingInterface $mapping
     * @param TileInterface[][][] $tiles
     * @param Box $tileSize
     */
    public function __construct(
        MappingInterface $mapping,
        array $tiles,
        Box $tileSize
    ) {
        $this->mapping = $mapping;
        $this->tiles = $tiles;
        $this->tileWidth = $tileSize->getWidth();
        $this->tileHeight = $tileSize->getHeight();
    }

    /**
     * @inheritDoc
     */
    public function getColorAt($latitude, $longitude)
    {
        $position = $this->mapping->getPosition($latitude, $longitude);
        $offsetX = (int) ($position[1] / $this->tileWidth);
        $offsetY = (int) ($position[2] / $this->tileHeight);
        return $this->tiles[$position[0]][$offsetY][$offsetX]
            ->getImage()
            ->getColorAt(
                $position[1] % $this->tileWidth,
                $position[2] % $this->tileHeight
            );
    }
}
