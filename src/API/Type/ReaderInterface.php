<?php

namespace AmaTeam\Image\Projection\API\Type;

/**
 * This interface describes an API for reading an abstract ellipsoid, recorded
 * in a cube map, equirectangular projection, or anything else.
 */
interface ReaderInterface
{
    /**
     * Retrieves color at specific location.
     *
     * @param float $latitude
     * @param float $longitude
     * @return int Color as int32 number: RRGGBBAA
     */
    public function getColorAt($latitude, $longitude);
}
