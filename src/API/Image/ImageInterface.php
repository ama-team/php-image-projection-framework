<?php

namespace AmaTeam\Image\Projection\API\Image;

use AmaTeam\Image\Projection\Image\EncodingOptions;

interface ImageInterface
{
    /**
     * @return int
     */
    public function getWidth();

    /**
     * @return int
     */
    public function getHeight();

    /**
     * @param int $x
     * @param int $y
     * @return int
     */
    public function getColorAt($x, $y);

    /**
     * @param int $x
     * @param int $y
     * @param int $color
     * @return void
     */
    public function setColorAt($x, $y, $color);

    /**
     * @param $format
     * @param EncodingOptions $options Backend-specific options
     * @return string
     */
    public function getBinary($format, EncodingOptions $options = null);

    /**
     * Returns underlying resource. Used for testing only.
     *
     * @internal
     *
     * @return mixed
     */
    public function getResource();
}
