<?php

namespace AmaTeam\Image\Projection\Conversion\Processor\FXAA;

class TargetColorCalculator
{
    /**
     * @param Edge $edge
     * @param float $offset
     * @return int
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public static function calculate(Edge $edge, $offset)
    {
        $colors = $edge->color;
        if ($offset < 0.01) {
            return $colors->center;
        }
        if ($edge->horizontal) {
            $mixed = $edge->inward ? $colors->north : $colors->south;
        } else {
            $mixed = $edge->inward ? $colors->west : $colors->east;
        }
        $sourceRed = ($colors->center >> 24) & 0xFF;
        $sourceGreen = ($colors->center >> 16) & 0xFF;
        $sourceBlue = ($colors->center >> 8) & 0xFF;
        $sourceAlpha = $colors->center & 0xFF;
        $mixedRed = ($mixed >> 24) & 0xFF;
        $mixedGreen = ($mixed >> 16) & 0xFF;
        $mixedBlue = ($mixed >> 8) & 0xFF;
        $mixedAlpha = $mixed & 0xFF;
        $multiplier = 1 - $offset;
        $red = min((int) round($sourceRed * $multiplier + $mixedRed * $offset), 0xFF);
        $blue = min((int) round($sourceBlue * $multiplier + $mixedBlue * $offset), 0xFF);
        $green = min((int) round($sourceGreen * $multiplier + $mixedGreen * $offset), 0xFF);
        $alpha = min((int) round($sourceAlpha * $multiplier + $mixedAlpha * $offset), 0xFF);
        return ($red << 24) | ($green << 16) | ($blue << 8) | $alpha;
    }
}
