<?php

namespace AmaTeam\Image\Projection\Framework\Filter;

use AmaTeam\Image\Projection\Framework\FilterInterface;
use AmaTeam\Image\Projection\Specification;
use AmaTeam\Image\Projection\Tile\Position;

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
    public function allows(Position $position, Specification $specification)
    {
        return in_array($position->getFace(), $this->faces, true);
    }
}
