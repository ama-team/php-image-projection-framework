<?php

namespace AmaTeam\Image\Projection\API\Type;

use AmaTeam\Image\Projection\API\ConversionOptionsInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;

interface HandlerInterface
{
    /**
     * Creates new accessor for projection described by specification
     *
     * @param SpecificationInterface $specification
     * @param ReaderOptionsInterface|null $options
     * @return ReaderInterface
     */
    public function createReader(
        SpecificationInterface $specification,
        ReaderOptionsInterface $options = null
    );

    /**
     * @param ReaderInterface $source
     * @param SpecificationInterface $target
     * @param ConversionOptionsInterface $options
     * @return GeneratorInterface Iterator that emits tiles.
     */
    public function createGenerator(
        ReaderInterface $source,
        SpecificationInterface $target,
        ConversionOptionsInterface $options = null
    );
}
