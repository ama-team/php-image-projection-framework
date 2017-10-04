<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\Tile\Position;
use AmaTeam\Image\Projection\Tile\Tile;
use Iterator;

interface GeneratorInterface extends Iterator
{
    /**
     * @return Tile
     */
    public function current();

    /**
     * @return Position
     */
    public function key();
}
