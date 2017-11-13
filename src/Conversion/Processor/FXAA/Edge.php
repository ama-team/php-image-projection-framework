<?php

namespace AmaTeam\Image\Projection\Conversion\Processor\FXAA;

use AmaTeam\Image\Projection\API\Image\ImageInterface;

class Edge
{
    /**
     * @var bool
     */
    public $horizontal;
    /**
     * @var bool
     */
    public $inward;
    /**
     * @var int
     */
    public $x;
    /**
     * @var int
     */
    public $y;
    /**
     * @var ImageInterface
     */
    public $image;
    /**
     * @var ColorGrid
     */
    public $color;
    /**
     * @var LumaGrid
     */
    public $luma;
    /**
     * @var float
     */
    public $averageLuma;
    /**
     * @var float
     */
    public $gradient;
    /**
     * @var float
     */
    public $threshold;
    /**
     * @var int
     */
    public $forwardDistance;
    /**
     * @var int
     */
    public $backwardDistance;
}
