<?php

namespace AmaTeam\Image\Projection\Type\CubeMap;

use AmaTeam\Image\Projection\Type\AbstractValidatingMapping;
use AmaTeam\Image\Projection\Type\CubeMap\Mapping\Face;
use AmaTeam\Image\Projection\Type\CubeMap\Mapping\Vector;
use BadMethodCallException;

class Mapping extends AbstractValidatingMapping
{
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

    public function __construct()
    {
        $this->faces = Face::generateCubeFaces();
        $this->faceNameMap = Face::getNames();
        $this->inverseFaceNameMap = array_flip($this->faceNameMap);
    }

    /**
     * Converts UV mapping to latitude/longitude.
     *
     * @param int|string $faceIndex
     * @param float $u
     * @param float $v
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
        // mangling vector since classic XYZ don't map to cube map XYZ
        $vector = [$vector[2], $vector[0], $vector[1], $vector[3]];
        return Vector::toPolar($vector);
    }

    /**
     * @param float $longitude
     * @param float $latitude
     *
     * @return array Array of [Face, int (u), int (v)]
     */
    public function getPosition($latitude, $longitude)
    {
        $vector = Vector::fromPolar($latitude, $longitude);
        // mangling vector since classic XYZ don't map to cube map XYZ
        $vector = [$vector[1], $vector[2], $vector[0], 1];
        $dominant = Vector::getDominant($vector);
        $value = $vector[$dominant];
        $vector = Vector::multiply($vector, abs(1 / $value));
        $faceIndex = ($dominant * 2) + ($value < 0 ? 1 : 0);
        $face = $this->faces[$faceIndex];
        $position = $face->map($vector);
        return [
            $this->faceNameMap[$faceIndex],
            $position[0],
            $position[1]
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
}
