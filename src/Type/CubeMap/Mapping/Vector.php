<?php

namespace AmaTeam\Image\Projection\Type\CubeMap\Mapping;

class Vector
{
    /**
     * @param float $x
     * @param float $y
     * @param float $z
     * @param null|float $length Vector length. If not set, will be computed,
     *   wasting resources.
     * @return float[] Array of (latitude, longitude) in radians.
     */
    public static function convert($x, $y, $z, $length = null)
    {
        $length = $length ?: sqrt(pow($x, 2) + pow($y, 2) + pow($z, 2));
        $normalized = $length ? $z / $length : 0;
        return [asin($normalized), atan2($y, $x)];
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param float $maxLength
     * @return float[] Array of [x, y, z] in [-1..1] range
     */
    public static function create($latitude, $longitude, $maxLength = null)
    {
        $z = sin($latitude);
        $xy = cos($latitude);
        $x = cos($longitude) * $xy;
        $y = sin($longitude) * $xy;
        // todo possible optimizations
        $multiplier = $maxLength === null ? 1 : $maxLength / max(abs($x), abs($y), abs($z));
        return [$x * $multiplier, $y * $multiplier, $z * $multiplier];
    }
}
