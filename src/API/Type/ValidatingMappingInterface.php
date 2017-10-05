<?php

namespace AmaTeam\Image\Projection\API\Type;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\Tile\Tile;
use BadMethodCallException;

interface ValidatingMappingInterface extends MappingInterface
{
    /**
     * @param Tile[][][] $tiles Tiles to validate
     * @param SpecificationInterface $specification
     * @throws BadMethodCallException
     * @return void
     */
    public function validate(
        array $tiles,
        SpecificationInterface $specification
    );
}
