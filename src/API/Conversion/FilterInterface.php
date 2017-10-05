<?php

namespace AmaTeam\Image\Projection\API\Conversion;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\PositionInterface;

/**
 * Filter allows preventing specific tiles from being generated.
 */
interface FilterInterface
{
    /**
     * Returns true if filter has nothing against specified tile generation,
     * false otherwise.
     *
     * @param PositionInterface $position
     * @param SpecificationInterface $specification
     * @return bool
     */
    public function allows(
        PositionInterface $position,
        SpecificationInterface $specification
    );
}
