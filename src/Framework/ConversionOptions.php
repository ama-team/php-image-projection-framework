<?php

namespace AmaTeam\Image\Projection\Framework;

use AmaTeam\Image\Projection\API\Conversion\FilterInterface;
use AmaTeam\Image\Projection\API\ConversionOptionsInterface;

class ConversionOptions implements ConversionOptionsInterface
{
    /**
     * @var string
     */
    private $interpolationMode = self::INTERPOLATION_BILINEAR;
    /**
     * @var FilterInterface[]
     */
    private $filters = [];

    /**
     * @return string
     */
    public function getInterpolationMode()
    {
        return $this->interpolationMode;
    }

    /**
     * @param string $interpolationMode
     * @return $this
     */
    public function setInterpolationMode($interpolationMode)
    {
        $this->interpolationMode = $interpolationMode;
        return $this;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param FilterInterface[] $filters
     * @return $this
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }
}
