<?php

namespace AmaTeam\Image\Projection\API;

use AmaTeam\Image\Projection\Specification;

interface ConverterInterface
{
    /**
     * @param SpecificationInterface $source
     * @param SpecificationInterface $target
     * @return ConversionInterface
     */
    public function createConversion(
        SpecificationInterface $source,
        SpecificationInterface $target
    );

    /**
     * @param SpecificationInterface $source
     * @param Specification[] $targets
     * @return ConversionInterface
     */
    public function createConversions(
        SpecificationInterface $source,
        array $targets
    );
}
