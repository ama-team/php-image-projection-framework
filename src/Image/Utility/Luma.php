<?php

namespace AmaTeam\Image\Projection\Image\Utility;

class Luma
{
    /**
     * @param int $color
     * @return float
     */
    public static function compute($color)
    {
        $red = ($color >> 24) & 0xFF;
        $green = ($color >> 16) & 0xFF;
        $blue = ($color >> 8) & 0xFF;
        return (0.2126 * $red + 0.7152 * $green + 0.0722 * $blue) / 255;
    }
}
