<?php

namespace AmaTeam\Image\Projection\Conversion\Processor\FXAA;

use AmaTeam\Image\Projection\Image\Utility\Luma;

class LumaGrid
{
    /**
     * @var float
     */
    public $center;
    /**
     * @var float
     */
    public $north;
    /**
     * @var float
     */
    public $northEast;
    /**
     * @var float
     */
    public $east;
    /**
     * @var float
     */
    public $southEast;
    /**
     * @var float
     */
    public $south;
    /**
     * @var float
     */
    public $southWest;
    /**
     * @var float
     */
    public $west;
    /**
     * @var float
     */
    public $northWest;
    /**
     * @var float
     */
    public $min;
    /**
     * @var float
     */
    public $max;
    /**
     * @var float
     */
    public $range;

    public function fill(ColorGrid $grid)
    {
        $this->center = Luma::compute($grid->center);
        $this->north = Luma::compute($grid->north);
        $this->northEast = Luma::compute($grid->northEast);
        $this->east = Luma::compute($grid->east);
        $this->southEast = Luma::compute($grid->southEast);
        $this->south = Luma::compute($grid->south);
        $this->southWest = Luma::compute($grid->southWest);
        $this->west = Luma::compute($grid->west);
        $this->northWest = Luma::compute($grid->northWest);
        $this->min = min($this->center, $this->north, $this->east, $this->south, $this->west);
        $this->max = max($this->center, $this->north, $this->east, $this->south, $this->west);
        $this->range = $this->max - $this->min;
    }
    
    public static function fromArray(array $values)
    {
        $grid = new LumaGrid();
        $map = [
            'northWest', 'north', 'northEast',
            'west', 'center', 'east',
            'southWest', 'south', 'southEast'
        ];
        foreach ($map as $position => $property) {
            $grid->$property = $values[$position];
        }
        $grid->min = min($grid->center, $grid->north, $grid->east, $grid->south, $grid->west);
        $grid->max = max($grid->center, $grid->north, $grid->east, $grid->south, $grid->west);
        $grid->range = $grid->max - $grid->min;
        return $grid;
    }
}
