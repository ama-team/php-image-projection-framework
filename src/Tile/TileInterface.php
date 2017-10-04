<?php

namespace AmaTeam\Image\Projection\Tile;

use AmaTeam\Image\Projection\Image\Adapter\ImageInterface;

interface TileInterface
{
    /**
     * @return Position
     */
    public function getPosition();

    /**
     * @return ImageInterface
     */
    public function getImage();
}
