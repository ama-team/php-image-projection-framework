<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Type\CubeMap;

use AmaTeam\Image\Projection\Test\Suite\Unit\Type\MappingTestSuite;
use AmaTeam\Image\Projection\Type\CubeMap\Mapping;
use AmaTeam\Image\Projection\Type\CubeMap\Mapping\Face;

class MappingTest extends MappingTestSuite
{
    public function dataProvider()
    {
        return [
            [
                [0, 0],
                [Face::FRONT, 0.5, 0.5],
            ],
            [
                [atan(0.5), 0],
                [Face::FRONT, 0.5, 0.25],
            ],
            [
                [0, -atan(0.5)],
                [Face::FRONT, 0.25, 0.5],
            ],
            [
                [0, M_PI],
                [Face::BACK, 0.499, 0.5],
            ],
            [
                [0, M_PI - atan(0.5)],
                [Face::BACK, 0.25, 0.5],
            ],
            [
                [atan(0.5), M_PI],
                [Face::BACK, 0.499, 0.25],
            ],
            [
                [0, M_PI / 2],
                [Face::RIGHT, 0.5, 0.5],
            ],
            [
                [-M_PI / 2, 0],
                [Face::DOWN, 0.5, 0.499],
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createMapping()
    {
        return new Mapping();
    }
}
