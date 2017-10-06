<?php

namespace AmaTeam\Image\Projection\Tile;

use AmaTeam\Image\Projection\API\Image\ImageInterface;
use AmaTeam\Image\Projection\API\Tile\PositionInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;

/**
 * Represents single tile - rectangular projection chunk.
 */
class Tile implements TileInterface
{
    /**
     * @var PositionInterface
     */
    private $position;
    /**
     * @var ImageInterface
     */
    private $image;

    /**
     * @param PositionInterface $position
     * @param ImageInterface $image
     */
    public function __construct(
        PositionInterface $position,
        ImageInterface $image
    ) {
        $this->position = $position;
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return PositionInterface
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Converts single level tiles array into three level tree
     * (face -> rows -> columns).
     *
     * @param TileInterface[] $tiles
     * @return TileInterface[][][]
     */
    public static function treeify(array $tiles)
    {
        $target = [];
        foreach ($tiles as $tile) {
            $position = $tile->getPosition();
            $cursor = &$target;
            foreach ([$position->getFace(), $position->getY()] as $segment) {
                if (!isset($cursor[$segment])) {
                    $cursor[$segment] = [];
                }
                $cursor = &$cursor[$segment];
            }
            $cursor[$position->getX()] = $tile;
        }
        return $target;
    }
}
