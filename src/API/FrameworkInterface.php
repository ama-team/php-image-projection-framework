<?php

namespace AmaTeam\Image\Projection\API;

use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\API\Image\Format;
use AmaTeam\Image\Projection\Specification;

interface FrameworkInterface
{
    /**
     * @param Specification $source
     * @param Specification $target
     * @param string $format
     * @param EncodingOptions|null $options
     * @return void
     */
    public function convert(
        Specification $source,
        Specification $target,
        $format = Format::JPEG,
        EncodingOptions $options = null
    );

    /**
     * @param Specification $source
     * @param Specification[] $targets
     * @param string $format
     * @param EncodingOptions|null $options
     * @return void
     */
    public function convertAll(
        Specification $source,
        array $targets,
        $format = Format::JPEG,
        EncodingOptions $options = null
    );
}
