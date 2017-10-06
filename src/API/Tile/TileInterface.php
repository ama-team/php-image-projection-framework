<?php

namespace AmaTeam\Image\Projection\API\Tile;

use AmaTeam\Image\Projection\API\Image\ImageInterface;

interface TileInterface
{
    /**
     * @return PositionInterface
     */
    public function getPosition();

    /**
     * @return ImageInterface
     */
    public function getImage();
}
