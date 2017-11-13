<?php

namespace AmaTeam\Image\Projection\Type\Equirectangular;

use AmaTeam\Image\Projection\Type\AbstractValidatingMapping;

/**
 * This class maps coordinates to points and vice versa.
 */
class Mapping extends AbstractValidatingMapping
{
    /**
     * @inheritDoc
     */
    public function getPosition($latitude, $longitude)
    {
        return [
            self::DEFAULT_FACE,
            ($longitude + self::PI) / self::DOUBLE_PI,
            (self::PI - ($latitude + self::PI_HALF)) / self::PI
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCoordinates($face, $u, $v)
    {
        return [
            self::PI_HALF - ($v * self::PI),
            $u * self::DOUBLE_PI - self::PI
        ];
    }

    public function getFaces()
    {
        return [self::DEFAULT_FACE];
    }
}
