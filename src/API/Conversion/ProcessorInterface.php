<?php

namespace AmaTeam\Image\Projection\API\Conversion;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\Tile\Tile;

/**
 * Interface for tile post-processor that allows to further twiddle tile
 * (e.g. apply FXAA).
 */
interface ProcessorInterface
{
    /**
     * @param Tile $tile
     * @param SpecificationInterface $specification
     * @return void
     */
    public function process(Tile $tile, SpecificationInterface $specification);
}
