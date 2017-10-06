<?php

namespace AmaTeam\Image\Projection\API\Conversion;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;

/**
 * Interface for tile post-processor that allows to further twiddle tile
 * (e.g. apply FXAA).
 */
interface ProcessorInterface
{
    /**
     * @param TileInterface $tile
     * @param SpecificationInterface $specification
     * @return void
     */
    public function process(
        TileInterface $tile,
        SpecificationInterface $specification
    );
}
