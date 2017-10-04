<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\Framework\FilterInterface;
use AmaTeam\Image\Projection\Specification;

interface HandlerInterface
{
    /**
     * Creates new accessor for projection described by specification
     *
     * @param Specification $specification
     * @return ReaderInterface
     */
    public function read(Specification $specification);

    /**
     * @param ReaderInterface $source
     * @param Specification $target
     * @param FilterInterface[] $filters
     * @return GeneratorInterface Iterator that emits tiles.
     */
    public function createGenerator(
        ReaderInterface $source,
        Specification $target,
        array $filters = []
    );
}
