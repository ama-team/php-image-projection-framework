<?php

namespace AmaTeam\Image\Projection\Image\Adapter\Imagick;

use Imagick;
use ImagickPixel;

class Color
{
    const COMPONENTS = [
        Imagick::COLOR_RED,
        Imagick::COLOR_GREEN,
        Imagick::COLOR_BLUE,
        Imagick::COLOR_ALPHA
    ];

    /**
     * TODO: this black bit magic is probably optimizable
     *
     * @param ImagickPixel $pixel
     * @return int
     */
    public static function get(ImagickPixel $pixel)
    {
        $color = 0;
        for ($i = 0; $i < 4; $i++) {
            $component = $pixel->getColorValue(self::COMPONENTS[$i]);
            $component = ((int) ($component * 0xFF)) & 0xFF;
            $color |= $component << ((3 - $i) * 8);
        }
        return $color;
    }

    /**
     * @param ImagickPixel $pixel
     * @param int $color
     */
    public static function set(ImagickPixel $pixel, $color)
    {
        for ($i = 0; $i < 4; $i++) {
            $component = ($color >> ((3 - $i) * 8)) & 0xFF;
            $pixel->setColorValue(self::COMPONENTS[$i], $component / 255.0);
        }
    }
}
