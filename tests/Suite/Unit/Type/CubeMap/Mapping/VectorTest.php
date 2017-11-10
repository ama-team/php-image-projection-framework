<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Type\CubeMap\Mapping;

use AmaTeam\Image\Projection\Type\CubeMap\Mapping\Vector;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;

class VectorTest extends Unit
{
    const PI = M_PI;
    const PI_HALF = M_PI / 2;
    const PI_QUARTER = M_PI / 4;

    public function dataProvider()
    {
        return [
            // circle axis intersection
            [[0, 0], [1, 0, 0, 1]],
            [[0, self::PI_HALF], [0, 1, 0, 1]],
            [[0, self::PI], [-1, 0, 0, 1]],
            [[0, -self::PI_HALF], [0, -1, 0, 1]],

            // z minimum / maximum
            [[-self::PI_HALF, 0], [0, 0, -1, 1]],
            [[self::PI_HALF, 0], [0, 0, 1, 1]],

            // calculated values

            // circle inner square corners
            [[0, self::PI_QUARTER], [sqrt(0.5), sqrt(0.5), 0, 1]],
            [[0, 3 * self::PI_QUARTER], [-sqrt(0.5), sqrt(0.5), 0, 1]],
            [[0, -3 * self::PI_QUARTER], [-sqrt(0.5), -sqrt(0.5), 0, 1]],
            [[0, -self::PI_QUARTER], [sqrt(0.5), -sqrt(0.5), 0, 1]],
        ];
    }

    public function toPolarDataProvider()
    {
        return [
            [Vector::fromCartesian(1, 1, 1), [asin(1 / sqrt(3)), M_PI / 4]]
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param float[] $coordinates
     * @param float[] $vector
     */
    public function testConversion($coordinates, $vector)
    {
        $created = Vector::fromPolar($coordinates[0], $coordinates[1]);
        self::validateArrays($vector, $created);
        $converted = Vector::toPolar($created);
        self::validateArrays($coordinates, $converted);
    }

    /**
     * @dataProvider toPolarDataProvider
     * @param float[] $vector
     * @param float[] $coordinates
     */
    public function testToPolarConversion(array $vector, array $coordinates)
    {
        self::validateArrays($coordinates, Vector::toPolar($vector));
    }

    private static function validateArrays(array $expected, array $actual)
    {
        Assert::assertSameSize($expected, $actual);
        for ($i = 0; $i < sizeof($expected); $i++) {
            $message = "Element $i differs: expected {$expected[$i]}, got " .
                $actual[$i];
            Assert::assertEquals($expected[$i], $actual[$i], $message, 0.1);
        }
    }
}
