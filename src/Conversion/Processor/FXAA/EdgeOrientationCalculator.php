<?php

namespace AmaTeam\Image\Projection\Conversion\Processor\FXAA;

class EdgeOrientationCalculator
{
    public static function apply(Edge $edge)
    {
        $grid = $edge->luma;
        $vertical =
            abs(($grid->northWest / 4) - ($grid->north / 2) + ($grid->northEast / 4)) +
            abs(($grid->west / 2) - $grid->center + ($grid->east / 2)) +
            abs(($grid->southWest) / 4) - ($grid->south / 2) + ($grid->southEast / 4);
        $horizontal =
            abs(($grid->northWest / 4) - ($grid->west / 2) + ($grid->southWest / 4)) +
            abs(($grid->north / 2) - $grid->center + ($grid->south / 2)) +
            abs(($grid->northEast / 4) - ($grid->east / 2) + ($grid->southEast / 4));
        $edge->horizontal = $horizontal >= $vertical;
    }
}
