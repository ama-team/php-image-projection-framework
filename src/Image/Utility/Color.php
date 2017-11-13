<?php

namespace AmaTeam\Image\Projection\Image\Utility;

class Color
{
    /**
     * @param int $target
     * @param int $source
     * @return int
     */
    public static function blend($target, $source)
    {
        $targetAlpha = $target & 0xFF;
        $sourceAlpha = $source & 0xFF;
        $influence = $sourceAlpha / $targetAlpha;
        $result = ((int) ($targetAlpha + (255 - $targetAlpha) * ($sourceAlpha / 255))) & 0xFF;
        for ($i = 3; $i >= 1; $i--) {
            $shift = $i * 8;
            $targetColor = ($target >> $shift) & 0xFF;
            $sourceColor = ($source >> $shift) & 0xFF;
            $difference = $sourceColor - $targetColor;
            $color = $targetColor + $difference * $influence;
            $result |= (((int) $color) & 0xFF) << $shift;
        }
        return $result;
    }
}
