<?php

namespace AmaTeam\Image\Projection\Conversion\Processor\FXAA;

use AmaTeam\Image\Projection\API\Image\ImageInterface;

class ColorGrid
{
    /**
     * @var int
     */
    public $center;
    /**
     * @var int
     */
    public $north;
    /**
     * @var int
     */
    public $northEast;
    /**
     * @var int
     */
    public $east;
    /**
     * @var int
     */
    public $southEast;
    /**
     * @var int
     */
    public $south;
    /**
     * @var int
     */
    public $southWest;
    /**
     * @var int
     */
    public $west;
    /**
     * @var int
     */
    public $northWest;

    /**
     * @param ImageInterface $image
     * @param int $x
     * @param int $y
     */
    public function fill(ImageInterface $image, $x, $y)
    {
        $width = $image->getWidth();
        $height = $image->getHeight();
        $nextX = $x === $width - 1 ? $x : $x + 1;
        $prevX = $x === 0 ? 0 : $x - 1;
        $nextY = $y === $height - 1 ? $y : $y + 1;
        $prevY = $y === 0 ? 0 : $y - 1;
        $this->center = $image->getColorAt($x, $y);
        $this->north = $image->getColorAt($x, $prevY);
        $this->northEast = $image->getColorAt($nextX, $prevY);
        $this->east = $image->getColorAt($nextX, $y);
        $this->southEast = $image->getColorAt($nextX, $nextY);
        $this->south = $image->getColorAt($x, $nextY);
        $this->southWest = $image->getColorAt($prevX, $nextY);
        $this->west = $image->getColorAt($prevX, $y);
        $this->northWest = $image->getColorAt($prevX, $prevY);
    }

    public static function fromArray(array $values)
    {
        $grid = new ColorGrid();
        $map = [
            'northWest', 'north', 'northEast',
            'west', 'center', 'east',
            'southWest', 'south', 'southEast'
        ];
        foreach ($map as $position => $property) {
            $grid->$property = $values[$position];
        }
        return $grid;
    }
}
