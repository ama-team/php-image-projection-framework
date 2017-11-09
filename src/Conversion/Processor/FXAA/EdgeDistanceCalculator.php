<?php

namespace AmaTeam\Image\Projection\Conversion\Processor\FXAA;

use AmaTeam\Image\Projection\Image\Utility\Luma;

class EdgeDistanceCalculator
{
    /**
     * @param Edge $edge
     * @param bool $inverse
     * @return int
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public static function calculate(Edge $edge, $inverse = false)
    {
        $width = $edge->image->getWidth();
        $height = $edge->image->getHeight();
        $offset = $inverse ? -1 : 1;
        if ($edge->horizontal) {
            $xOffset = $offset;
            $yOffset = 0;
            $xSampleOffset = 0;
            $ySampleOffset = $edge->inward ? -1 : 1;
        } else {
            $xOffset = 0;
            $yOffset = $offset;
            $xSampleOffset = $edge->inward ? -1 : 1;
            $ySampleOffset = 0;
        }
        $distance = 1;
        $averageLuma = $edge->averageLuma;
        for ($i = 0; $i < 10; $i++) {
            $edgeX = $edge->x + $distance * $xOffset;
            $edgeY = $edge->y + $distance * $yOffset;
            if ($edgeX < 0 || $edgeX >= $width || $edgeY < 0 || $edgeY >= $height) {
                break;
            }
            $sampleX = $edgeX + $xSampleOffset;
            $sampleY = $edgeY + $ySampleOffset;
            if ($sampleX < 0 || $sampleX >= $width || $sampleY < 0 || $sampleY >= $height) {
                break;
            }
            $edgeColor = $edge->image->getColorAt($edgeX, $edgeY);
            $sampleColor = $edge->image->getColorAt($sampleX, $sampleY);
            $edgeLuma = Luma::compute($edgeColor);
            $sampleLuma = Luma::compute($sampleColor);
            $luma = ($edgeLuma + $sampleLuma) / 2;
            if (abs($averageLuma - $luma) >= $edge->threshold) {
                break;
            }
            $multiplier = (int) ($distance / 4);
            $distance += 1 + $multiplier * $multiplier;
        }
        return $distance;
    }

    public static function apply(Edge $edge)
    {
        $edge->forwardDistance = self::calculate($edge, false);
        $edge->backwardDistance = self::calculate($edge, true);
    }
}
