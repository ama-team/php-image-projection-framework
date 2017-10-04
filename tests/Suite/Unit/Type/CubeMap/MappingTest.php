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
                [1000],
                [0, 0],
                [Face::FRONT, 500, 500],
            ],
            [
                [1000],
                [atan(0.5), 0],
                [Face::FRONT, 500, 250],
            ],
            [
                [1000],
                [0, -atan(0.5)],
                [Face::FRONT, 250, 500],
            ],
            [
                [1000],
                [0, M_PI],
                [Face::BACK, 500, 500],
            ],
            [
                [1000],
                [0, M_PI - atan(0.5)],
                [Face::BACK, 250, 500],
            ],
            [
                [1000],
                [atan(0.5), M_PI],
                [Face::BACK, 499.99, 250],
            ],
            [
                [1000],
                [0, M_PI / 2],
                [Face::RIGHT, 500, 500],
            ],
            [
                [1000],
                [-M_PI / 2, 0],
                [Face::DOWN, 500, 500],
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createMapping(array $parameters)
    {
        return new Mapping(reset($parameters));
    }
}
