<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Type\Equirectangular;

use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\Test\Suite\Unit\Type\MappingTestSuite;
use AmaTeam\Image\Projection\Type\Equirectangular\Mapping;

class MappingTest extends MappingTestSuite
{
    public function dataProvider()
    {
        return [
            [
                [0, 0],
                [MappingInterface::DEFAULT_FACE, 0.5, 0.5]
            ],
            [
                [M_PI / 2, 0],
                [MappingInterface::DEFAULT_FACE, 0.5, 0]
            ],
            [
                [0, -M_PI],
                [MappingInterface::DEFAULT_FACE, 0, 0.5]
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function createMapping()
    {
        return new Mapping(1000, 1000);
    }
}
