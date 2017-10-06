<?php

namespace AmaTeam\Image\Projection\Tile;

use AmaTeam\Image\Projection\API\Tile\PositionInterface;

/**
 * Represents tile position in projection
 */
class Position implements PositionInterface
{
    /**
     * @var string
     */
    private $face;
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @param string $face
     * @param int $x
     * @param int $y
     */
    public function __construct($face, $x, $y)
    {
        $this->face = $face;
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return string
     */
    public function getFace()
    {
        return $this->face;
    }

    /**
     * @param string $face
     * @return $this
     */
    public function setFace($face)
    {
        $this->face = $face;
        return $this;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     * @return $this
     */
    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     * @return $this
     */
    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }

    public function toPatternParameters()
    {
        return [
            'x' => $this->x,
            'h' => $this->x,
            'horizontal' => $this->x,
            'y' => $this->y,
            'v' => $this->y,
            'vertical' => $this->y,
            'f' => $this->face,
            'face' => $this->face
        ];
    }

    public function __toString()
    {
        $pattern = '{face: %s, x: %s, y: %s}';
        return sprintf($pattern, $this->face, $this->x, $this->y);
    }
}
