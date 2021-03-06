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
                [0.5, 0.5],
                [0, 0, 1]
            ],
            [
                Face::FRONT_DEFINITION,
                [0, 0],
                [-1, 1, 1]
            ],
            [
                Face::FRONT_DEFINITION,
                [1, 1],
                [1, -1, 1]
            ],
            [
                Face::BACK_DEFINITION,
                [0, 0],
                [1, 1, -1]
            ],
            [
                Face::BACK_DEFINITION,
                [1, 1],
                [-1, -1, -1]
            ],
            [
                Face::RIGHT_DEFINITION,
                [0, 0],
                [1, 1, 1]
            ],
            [
                Face::RIGHT_DEFINITION,
                [1, 1],
                [1, -1, -1]
            ],
            [
                Face::LEFT_DEFINITION,
                [0, 0],
                [-1, 1, -1]
            ],
            [
                Face::UP_DEFINITION,
                [0, 0],
                [-1, 1, -1]
            ],
            [
                Face::DOWN_DEFINITION,
                [0, 0],
                [-1, -1, 1]
            ],
        ];
    }

    /**
     * @param array $config
     * @param array $position
     * @param array $vector
     *
     * @dataProvider mappingDataProvider
     */
    public function testMapping(array $config, array $position, array $vector)
    {
        $face = Face::create($config);
        $vectorized = $face->vectorize($position[0], $position[1]);
        ksort($vectorized);
        array_splice($vectorized, 3);
        self::assertEqualCoordinates($vector, $vectorized, 3);
        $mapped = $face->map($vector);
        self::assertEqualCoordinates($position, $mapped);
    }

    private static function assertEqualCoordinates(array $a, array $b, $length = null)
    {
        $length = isset($length) ? $length : sizeof($a);
        for ($i = 0; $i < $length; $i++) {
            Assert::assertEquals($a[$i], $b[$i], '', 0.01);
        }
    }
}
