<?php

namespace AmaTeam\Image\Projection\Type;

interface MappingInterface
{
    /**
     * @param float $latitude
     * @param float $longitude
     * @return array [face:string, u:int, v:int]
     */
    public function getPosition($latitude, $longitude);

    /**
     * @param int|string $face
     * @param int $u
     * @param int $v
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
