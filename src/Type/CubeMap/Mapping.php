<?php

namespace AmaTeam\Image\Projection\Type\CubeMap;

use AmaTeam\Image\Projection\Type\CubeMap\Mapping\Face;
use AmaTeam\Image\Projection\Type\CubeMap\Mapping\Vector;
use AmaTeam\Image\Projection\Type\MappingInterface;

class Mapping implements MappingInterface
{
    /**
     * @var int
     */
    private $size;
    /**
     * @var int|float
     */
    private $halfSize;

    /**
     * @var Face[]
     */
    private $faces;
    /**
     * @var string[]
     */
    private $faceNameMap;
    /**
     * @var int[]
     */
    private $inverseFaceNameMap;

    /**
     * @param int $size
     */
    public function __construct($size)
    {
        // the -1 part is added to ensure that face of width/height N
        // would never report U or V = N, because that is actually an
        // out-of-bounds number - face has only N - 1 texels
        // TODO: ensure that all calculations are done right
        $this->size = $size - 1;
        $this->halfSize = ($size - 1) / 2;
        $this->faces = Face::generateCubeFaces($size);
        $this->faceNameMap = Face::getNames();
        $this->inverseFaceNameMap = array_flip($this->faceNameMap);
    }

    /**
     * Converts UV mapping to latitude/longitude.
     *
     * @param int|string $faceIndex
     * @param int $u
     * @param int $v
     *
     * @return float[]
     */
    public function getCoordinates($faceIndex, $u, $v)
    {
        if (is_string($faceIndex)) {
            $faceIndex = $this->inverseFaceNameMap[$faceIndex];
        }
        $face = $this->faces[$faceIndex];
        $vector = $face->vectorize($u, $v);
        return Vector::convert($vector[0], $vector[1], $vector[2], $vector[3]);
    }

    /**
     * @param float $longitude
     * @param float $latitude
     *
     * @return array Array of [Face, int (u), int (v)]
     */
    public function getPosition($latitude, $longitude)
    {
        $vector = Vector::create($latitude, $longitude, $this->halfSize);
        $dominant = self::getDominant($vector);
        $value = $vector[$dominant];
        $faceIndex = ($dominant * 2) + ($value < 0 ? 1 : 0);
        $face = $this->faces[$faceIndex];
        $position = $face->map($vector);
        return [
            $this->faceNameMap[$faceIndex],
            (int) $position[0],
            (int) $position[1]
        ];
    }

    /**
     * @param int|string $index
     * @return Face
     */
    public function getFace($index)
    {
        return $this->faces[$index];
    }

    /**
     * @inheritDoc
     */
    public function getFaces()
    {
        return $this->faceNameMap;
    }

    /**
     * @param array $vector
     * @return int
     */
    private static function getDominant(array $vector)
    {
        $index = 0;
        $maximum = abs($vector[0]);
        // Intentional loop unrolling
        if (abs($vector[1]) > $maximum) {
            $index = 1;
            $maximum = abs($vector[1]);
        }
        return abs($vector[2]) > $maximum ? 2 : $index;
    }
}
