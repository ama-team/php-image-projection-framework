<?php

namespace AmaTeam\Image\Projection\Type\CubeMap\Mapping;

/**
 * This is a helper class for vector operations. Since the whole
 * framework implies millions of iterations, everything is kept as
 * simple as possible, and vector is represented as a four-element
 * indexed array: 0 => x, 1 => y, 2 => z, 3 => computed length.
 */
class Vector
{
    /**
     * Creates vector from cartesian coordinates.
     *
     * @param float $x
     * @param float $y
     * @param float $z
     * @return float[] Created vector
     */
    public static function fromCartesian($x, $y, $z)
    {
        return [$x, $y, $z, sqrt($x * $x + $y * $y + $z * $z)];
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @return float[] Array of [x, y, z] in [-1..1] range
     */
    public static function fromPolar($latitude, $longitude)
    {
        $z = sin($latitude);
        $xyHypotenuse = cos($latitude);
        $x = cos($longitude) * $xyHypotenuse;
        $y = sin($longitude) * $xyHypotenuse;
        return [$x, $y, $z, 1];
    }

    /**
     * @param array $vector
     * @return float[] Array of (latitude, longitude) in radians.
     */
    public static function toPolar(array $vector)
    {
        if (!isset($vector[3]) || $vector[3] !== 1) {
            $vector = self::normalize($vector);
        }
        return [asin($vector[2]), atan2($vector[1], $vector[0])];
    }

    /**
     * Normalizes vector, setting it to target length.
     *
     * @param float[] $vector
     * @param int $length
     * @return float[]
     */
    public static function normalize(array $vector, $length = 1)
    {
        return self::multiply($vector, $length / $vector[3]);
    }

    /**
     * Multiplies all vector components
     *
     * @param float[] $vector
     * @param float|int $multiplier
     * @return float[]
     */
    public static function multiply(array $vector, $multiplier)
    {
        if ($multiplier === 1) {
            return $vector;
        }
        return [
            $vector[0] * $multiplier,
            $vector[1] * $multiplier,
            $vector[2] * $multiplier,
            $vector[3] * $multiplier
        ];
    }

    /**
     * Returns index of dominant vector component
     *
     * @param float[] $vector
     * @return int
     */
    public static function getDominant(array $vector)
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
