<?php

namespace AmaTeam\Image\Projection\Framework;

use AmaTeam\Image\Projection\Specification;
use AmaTeam\Image\Projection\Tile\Position;

/**
 * Filter allows preventing specific tiles from being generated.
 */
interface FilterInterface
{
    /**
     * Returns true if filter has nothing against specified tile generation,
     * false otherwise.
     *
     * @param Position $position
     * @param Specification $specification
     * @return bool
     */
    public function allows(Position $position, Specification $specification);
}
