<?php

namespace AmaTeam\Image\Projection\API\Type;

use AmaTeam\Image\Projection\API\SpecificationInterface;

interface HandlerInterface
{
    /**
     * Creates new accessor for projection described by specification
     *
     * @param SpecificationInterface $specification
     * @param SourceOptionsInterface|null $options
     * @return ReaderInterface
     */
    public function createReader(
        SpecificationInterface $specification,
        SourceOptionsInterface $options = null
    );

    /**
     * @param ReaderInterface $source
     * @param SpecificationInterface $target
     * @param TargetOptionsInterface $options
     * @return GeneratorInterface Iterator that emits tiles.
     */
    public function createGenerator(
        ReaderInterface $source,
        SpecificationInterface $target,
        TargetOptionsInterface $options = null
    );
}
