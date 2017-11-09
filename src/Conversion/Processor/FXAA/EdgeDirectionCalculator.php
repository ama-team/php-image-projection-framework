<?php

namespace AmaTeam\Image\Projection\Conversion\Processor\FXAA;

class EdgeDirectionCalculator
{
    public static function apply(Edge $edge)
    {
        $grid = $edge->luma;
        $inwardLuma = $edge->horizontal ? $grid->north : $grid->west;
        $outwardLuma = $edge->horizontal ? $grid->south : $grid->east;
        $inwardGradient = abs($grid->center - $inwardLuma);
        $outwardGradient = abs($grid->center - $outwardLuma);
        $edge->inward = $inwardGradient >= $outwardGradient;
    }
}
