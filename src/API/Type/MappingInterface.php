<?php

namespace AmaTeam\Image\Projection\API\Type;

interface MappingInterface
{
    const DEFAULT_FACE = '-';
    const PI = M_PI;
    const PI_HALF = M_PI / 2;
    const PI_QUARTER = M_PI / 4;
    const DOUBLE_PI = M_PI * 2;

    /**
     * @param float $latitude
     * @param float $longitude
     * @return array [face:string, u:float, v:float]
     */
    public function getPosition($latitude, $longitude);

    /**
     * @param int|string $face
     * @param float $u
     * @param float $v
     * @return float[] [latitude:float, longitude:float]
     */
    public function getCoordinates($face, $u, $v);

    /**
     * List of face names
     *
     * @return string[]
     */
    public function getFaces();
}
