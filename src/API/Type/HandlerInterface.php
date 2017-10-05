<?php

namespace AmaTeam\Image\Projection\API\Type;

use AmaTeam\Image\Projection\API\Conversion\FilterInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;

interface HandlerInterface
{
    /**
     * Creates new accessor for projection described by specification
     *
     * @param SpecificationInterface $specification
     * @return ReaderInterface
     */
    public function read(SpecificationInterface $specification);

    /**
     * @param ReaderInterface $source
     * @param SpecificationInterface $target
     * @param FilterInterface[] $filters
     * @return GeneratorInterface Iterator that emits tiles.
     */
    public function createGenerator(
        ReaderInterface $source,
        SpecificationInterface $target,
        array $filters = []
    );
}
