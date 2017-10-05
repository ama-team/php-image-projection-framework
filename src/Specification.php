<?php

namespace AmaTeam\Image\Projection;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\Filesystem\Pattern;
use AmaTeam\Image\Projection\Geometry\Box;

/**
 * Specification describes single projection: it's type, path (pattern) and
 * sizes (tile size, overall size, number of tiles in face row and column).
 */
class Specification implements SpecificationInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * Projection path (if applicable).
     *
     * @var Pattern
     */
    private $pattern;

    /**
     * Width and height of single tile in pixels (texels?)
     *
     * @var Box
     */
    private $tileSize;

    /**
     * Projection face layout: number of tiles in row and column.
     *
     * @var Box
     */
    private $layout;

    /**
     * Overall size of single face (basically tileSize x layout)
     *
     * @var Box
     */
    private $size;

    /**
     * @param string|null $type
     * @param string|null $pattern
     * @param Box|null $tileSize
     * @param Box|null $layout
     */
    public function __construct(
        $type = null,
        $pattern = null,
        $tileSize = null,
        $layout = null
    ) {
        $this->type = $type;
        $this->setPattern($pattern);
        $this->tileSize = $tileSize;
        $this->layout = $layout ?: new Box(1, 1);
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Pattern
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string|Pattern $pattern
     * @return $this
     */
    public function setPattern($pattern)
    {
        if ($pattern !== null && !($pattern instanceof Pattern)) {
            $pattern = new Pattern($pattern);
        }
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return Box
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param Box $layout
     * @return $this
     */
    public function setLayout(Box $layout)
    {
        $this->layout = $layout;
        $this->size = null;
        return $this;
    }

    /**
     * @return Box
     */
    public function getTileSize()
    {
        return $this->tileSize;
    }

    /**
     * @param Box $tileSize
     * @return $this
     */
    public function setTileSize(Box $tileSize)
    {
        $this->tileSize = $tileSize;
        $this->size = null;
        return $this;
    }

    /**
     * @return Box
     */
    public function getSize()
    {
        if (!$this->size && $this->layout && $this->tileSize) {
            $this->size = self::computeSize($this->layout, $this->tileSize);
        }
        return $this->size;
    }

    private static function computeSize(Box $layout, Box $tileSize)
    {
        return new Box(
            $layout->getWidth() * $tileSize->getWidth(),
            $layout->getHeight() * $tileSize->getHeight()
        );
    }
}
