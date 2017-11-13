<?php

namespace AmaTeam\Image\Projection\API;

use AmaTeam\Image\Projection\API\Conversion\FilterInterface;

interface ConversionOptionsInterface
{
    const INTERPOLATION_NONE = 'none';
    const INTERPOLATION_BILINEAR = 'bilinear';

    /**
     * @return string
     */
    public function getInterpolationMode();

    /**
     * @return FilterInterface[]
     */
    public function getFilters();
}
