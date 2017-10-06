<?php

namespace AmaTeam\Image\Projection\Framework\Validation\Tile;

use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Framework\Validation\ValidatorInterface;
use AmaTeam\Image\Projection\Geometry\Box;

class SingleTileValidator implements ValidatorInterface
{
    /**
     * @var Box|null
     */
    private $size;

    /**
     * @param Box $size
     */
    public function __construct(Box $size = null)
    {
        $this->size = $size;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, array $path = [])
    {
        if (!($value instanceof TileInterface)) {
            throw new ValidationException('Value is not a Tile', $path);
        }
        if (!$this->size) {
            return;
        }
        $height = $value->getImage()->getHeight();
        $width = $value->getImage()->getWidth();
        if ($height !== $this->size->getHeight()) {
            $template = 'Tile height is %d (%d expected)';
            $message = sprintf($template, $height, $this->size->getHeight());
            throw new ValidationException($message, $path);
        }
        if ($width !== $this->size->getWidth()) {
            $template = 'Tile width is %d (%d expected)';
            $message = sprintf($template, $width, $this->size->getWidth());
            throw new ValidationException($message, $path);
        }
    }
}
