<?php

namespace AmaTeam\Image\Projection\Conversion\Filter;

use AmaTeam\Image\Projection\API\Conversion\FilterInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\PositionInterface;

/**
 * Allows to generate tiles only of specific face.
 */
class FaceFilter implements FilterInterface
{
    /**
     * @var string[]
     */
    private $faces;

    /**
     * @param string[] $faces
     */
    public function __construct(...$faces)
    {
        $this->faces = $faces;
    }

    /**
     * @inheritDoc
     */
    public function allows(
        PositionInterface $position,
        SpecificationInterface $specification
    ) {
        return in_array($position->getFace(), $this->faces, true);
    }
}
