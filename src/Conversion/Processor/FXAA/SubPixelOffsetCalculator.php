<?php

namespace AmaTeam\Image\Projection\Conversion\Processor\FXAA;

class SubPixelOffsetCalculator
{
    /**
     * @param Edge $edge
     * @return float
     */
    public static function calculate(Edge $edge)
    {
        $thickness = $edge->backwardDistance + $edge->forwardDistance;
        return 0.5 - (min($edge->backwardDistance, $edge->forwardDistance) / $thickness);
    }
}
