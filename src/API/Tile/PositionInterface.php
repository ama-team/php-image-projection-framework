<?php

namespace AmaTeam\Image\Projection\API\Tile;

interface PositionInterface
{
    /**
     * @return string
     */
    public function getFace();

    /**
     * @return int
     */
    public function getX();

    /**
     * @return int
     */
    public function getY();
}
