<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Type\CubeMap\Mapping;

use AmaTeam\Image\Projection\Type\CubeMap\Mapping\Face;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;

class FaceTest extends Unit
{
    public function mappingDataProvider()
    {
        return [
            [
                Face::FRONT_DEFINITION,
                1000,
                [500, 500],
                [500, 0, 0]
            ],
            [
                Face::FRONT_DEFINITION,
                1000,
                [0, 0],
                [500, -500, 500]
            ],
            [
                Face::FRONT_DEFINITION,
                1000,
                [1000, 1000],
                [500, 500, -500]
            ],
            [
                Face::BACK_DEFINITION,
                1000,
                [0, 0],
                [-500, 500, 500]
            ],
            [
                Face::BACK_DEFINITION,
                1000,
                [1000, 1000],
                [-500, -500, -500]
            ],
            [
                Face::RIGHT_DEFINITION,
                1000,
                [0, 0],
                [500, 500, 500]
            ],
            [
                Face::LEFT_DEFINITION,
                1000,
                [0, 0],
                [-500, -500, 500]
            ],
            [
                Face::UP_DEFINITION,
                1000,
                [0, 0],
                [-500, -500, 500]
            ],
            [
                Face::DOWN_DEFINITION,
                1000,
                [0, 0],
                [500, -500, -500]
            ],
        ];
    }

    /**
     * @param array $config
     * @param $size
     * @param array $position
     * @param array $vector
     *
     * @dataProvider mappingDataProvider
     */
    public function testMapping(array $config, $size, array $position, array $vector)
    {
        $face = Face::create($config, $size);
        $vectorized = $face->vectorize($position[0], $position[1]);
        ksort($vectorized);
        self::assertEqualCoordinates($vector, array_slice($vectorized, 0, 3));
        $mapped = $face->map($vector);
        self::assertEqualCoordinates($position, $mapped);
    }

    private static function assertEqualCoordinates(array $a, array $b)
    {
        Assert::assertSameSize($a, $b);
        for ($i = 0; $i < sizeof($a); $i++) {
            Assert::assertEquals($a[$i], $b[$i], '', 0.01);
        }
    }
}
