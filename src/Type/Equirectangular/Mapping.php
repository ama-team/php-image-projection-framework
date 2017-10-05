<?php

namespace AmaTeam\Image\Projection\Type\Equirectangular;

use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use InvalidArgumentException;

/**
 * This class maps coordinates to points and vice versa.
 */
class Mapping implements MappingInterface
{
    /**
     * @var int
     */
    private $width;
    /**
     * @var int
     */
    private $height;
    /**
     * Texels per radian
     *
     * @var float
     */
    private $hResolution;
    /**
     * Texels per radian
     *
     * @var float
     */
    private $vResolution;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct($width, $height)
    {
        if ($width < 2 || $height < 2) {
            $message = 'Width and height could not be less than 2';
            throw new InvalidArgumentException($message);
        }
        $this->width = $width;
        $this->height = $height;
        $this->hResolution = ($this->width - 1) / (2 * M_PI);
        $this->vResolution = ($this->height - 1) / M_PI;
    }

    /**
     * @inheritDoc
     */
    public function getPosition($latitude, $longitude)
    {
        return [
            self::DEFAULT_FACE,
            (int) (($longitude + M_PI) * $this->hResolution),
            (int) (((M_PI / 2) - $latitude) * $this->vResolution)
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCoordinates($face, $u, $v)
    {
        return [
            (M_PI / 2) - ($v / $this->vResolution),
            ($u / $this->hResolution) - M_PI
        ];
    }

    public function getFaces()
    {
        return [self::DEFAULT_FACE];
    }

    /**
     * @param Box $box
     * @return Mapping
     */
    public static function fromBox(Box $box)
    {
        return new Mapping($box->getWidth(), $box->getHeight());
    }
}
