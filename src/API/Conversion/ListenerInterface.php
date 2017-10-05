<?php

namespace AmaTeam\Image\Projection\API\Conversion;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\Tile\Tile;

/**
 * Listeners allows to receive tile and create some side effect based on it -
 * create log message, notify build system, persist tile to disk.
 */
interface ListenerInterface
{
    /**
     * @param Tile $tile
     * @param SpecificationInterface $specification
     * @return void
     */
    public function accept(Tile $tile, SpecificationInterface $specification);
}
