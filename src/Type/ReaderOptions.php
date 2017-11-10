<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\Type\ReaderOptionsInterface;
use AmaTeam\Image\Projection\Constants;

class ReaderOptions implements ReaderOptionsInterface
{
    private $interpolationMode = Constants::INTERPOLATION_BILINEAR;

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
