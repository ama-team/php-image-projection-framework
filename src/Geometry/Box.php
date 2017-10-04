<?php

namespace AmaTeam\Image\Projection\Geometry;

class Box
{
    /**
     * @var int
     */
    private $height;
    /**
     * @var int
     */
    private $width;

    /**
     * @param int $height
     * @param int $width
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
}
