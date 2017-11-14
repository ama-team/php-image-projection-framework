<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\Type\SourceOptionsInterface;
use AmaTeam\Image\Projection\API\Type\Interpolation;

class SourceOptions implements SourceOptionsInterface
{
    /**
     * @var string
     */
    private $interpolationMode = Interpolation::BILINEAR;

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
}
