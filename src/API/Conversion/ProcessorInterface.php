<?php

namespace AmaTeam\Image\Projection\API\Conversion;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;

/**
 * Interface for tile post-processor that allows to further twiddle tile
 * (e.g. apply FXAA).
 *
 * Processor may operate on existing tile or return another one as a
 * replacement.
 */
interface ProcessorInterface
{
    /**
     * @param TileInterface $tile
     * @param SpecificationInterface $specification
     * @return TileInterface|null
     */
    public function process(
        TileInterface $tile,
        SpecificationInterface $specification
    );
}
