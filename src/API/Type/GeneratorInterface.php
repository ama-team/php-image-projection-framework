<?php

namespace AmaTeam\Image\Projection\API\Type;

use AmaTeam\Image\Projection\API\Tile\PositionInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use Iterator;

interface GeneratorInterface extends Iterator
{
    /**
     * @return TileInterface
     */
    public function current();

    /**
     * @return PositionInterface
     */
    public function key();
}
