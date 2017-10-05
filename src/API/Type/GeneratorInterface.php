<?php

namespace AmaTeam\Image\Projection\API\Type;

use AmaTeam\Image\Projection\API\Tile\PositionInterface;
use AmaTeam\Image\Projection\Tile\Tile;
use Iterator;

interface GeneratorInterface extends Iterator
{
    /**
     * @return Tile
     */
    public function current();

    /**
     * @return PositionInterface
     */
    public function key();
}
