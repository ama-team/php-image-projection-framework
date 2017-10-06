<?php

namespace AmaTeam\Image\Projection\API\Type;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use BadMethodCallException;

interface ValidatingMappingInterface extends MappingInterface
{
    /**
     * @param TileInterface[][][] $tiles Tiles to validate
     * @param SpecificationInterface $specification
     * @throws BadMethodCallException
     * @return void
     */
    public function validate(
        array $tiles,
        SpecificationInterface $specification
    );
}
