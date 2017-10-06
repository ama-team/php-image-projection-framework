<?php

namespace AmaTeam\Image\Projection\API;

use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\API\Image\Format;

interface FrameworkInterface
{
    /**
     * @param SpecificationInterface $source
     * @param SpecificationInterface $target
     * @param string $format
     * @param EncodingOptions|null $options
     * @return void
     */
    public function convert(
        SpecificationInterface $source,
        SpecificationInterface $target,
        $format = Format::JPEG,
        EncodingOptions $options = null
    );

    /**
     * @param SpecificationInterface $source
     * @param SpecificationInterface[] $targets
     * @param string $format
     * @param EncodingOptions|null $options
     * @return void
     */
    public function convertAll(
        SpecificationInterface $source,
        array $targets,
        $format = Format::JPEG,
        EncodingOptions $options = null
    );
}
