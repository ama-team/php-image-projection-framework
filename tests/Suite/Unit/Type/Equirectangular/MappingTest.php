<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Type\Equirectangular;

use AmaTeam\Image\Projection\Constants;
use AmaTeam\Image\Projection\Test\Suite\Unit\Type\MappingTestSuite;
use AmaTeam\Image\Projection\Type\Equirectangular\Mapping;

class MappingTest extends MappingTestSuite
{
    public function dataProvider()
    {
        return [
            [
                [2000, 1000],
                [0, 0],
                [Constants::DEFAULT_FACE, 999, 499]
            ],
            [
                [2000, 1000],
                [M_PI / 2, 0],
                [Constants::DEFAULT_FACE, 999, 0]
            ],
            [
                [2000, 1000],
                [0, -M_PI],
                [Constants::DEFAULT_FACE, 0, 499]
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createMapping(array $parameters)
    {
        return new Mapping($parameters[0], $parameters[1]);
    }
}
