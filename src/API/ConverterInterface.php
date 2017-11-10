<?php

namespace AmaTeam\Image\Projection\API;

use AmaTeam\Image\Projection\Specification;

interface ConverterInterface
{
    /**
     * @param SpecificationInterface $source
     * @param SpecificationInterface $target
     * @param ConversionOptionsInterface $options
     * @return ConversionInterface
     */
    public function createConversion(
        SpecificationInterface $source,
        SpecificationInterface $target,
        ConversionOptionsInterface $options = null
    );

    /**
     * @param SpecificationInterface $source
     * @param Specification[] $targets
     * @param ConversionOptionsInterface $options
     * @return ConversionInterface[]
     */
    public function createConversions(
        SpecificationInterface $source,
        array $targets,
        ConversionOptionsInterface $options = null
    );
}
