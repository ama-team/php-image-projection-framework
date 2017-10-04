<?php

namespace AmaTeam\Image\Projection\Framework;

use AmaTeam\Image\Projection\Specification;
use AmaTeam\Image\Projection\Tile\Tile;

/**
 * Interface for tile post-processor that allows to further twiddle tile
 * (e.g. apply FXAA).
 */
interface ProcessorInterface
{
    /**
     * @param Tile $tile
     * @param Specification $specification
     * @return void
     */
    public function process(Tile $tile, Specification $specification);
}
