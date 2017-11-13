<?php

namespace AmaTeam\Image\Projection\Conversion\Processor\FXAA;

class EdgeLumaCalculator
{
    /**
     * @param Edge $edge
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public static function apply(Edge $edge)
    {
        if ($edge->horizontal) {
            $comparedLuma = $edge->inward ? $edge->luma->north : $edge->luma->south;
        } else {
            $comparedLuma = $edge->inward ? $edge->luma->west : $edge->luma->east;
        }
        $edge->averageLuma = ($edge->luma->center + $comparedLuma) / 2;
        $edge->gradient = abs($edge->luma->center - $comparedLuma);
        $edge->threshold = $edge->gradient / 4;
    }
}
