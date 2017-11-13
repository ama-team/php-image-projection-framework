<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Conversion\Processor\FXAA;

use AmaTeam\Image\Projection\Conversion\Processor\FXAA\Edge;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\EdgeOrientationCalculator;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\LumaGrid;
use AmaTeam\Image\Projection\Test\Support\Assert;
use Codeception\Test\Unit;

class EdgeOrientationCalculatorTest extends Unit
{
    public function dataProvider()
    {
        $variants = [
            [
                [
                    0, 0, 0,
                    1, 1, 1,
                    0, 0, 0
                ],
                true
            ],
            [
                [
                    0, 0, 0,
                    0, 0, 0,
                    1, 1, 1
                ],
                true
            ],
            [
                [
                    0, 0, 1,
                    0, 0, 0.99,
                    1, 1, 1
                ],
                true
            ],
            [
                [
                    0, 1, 0,
                    0, 1, 0,
                    0, 0, 0
                ],
                false
            ]
        ];
        return array_map(function ($variant) {
            $edge = new Edge();
            $edge->luma = LumaGrid::fromArray($variant[0]);
            return [$edge, $variant[1]];
        }, $variants);
    }

    /**
     * @param Edge $edge
     * @param bool $expectedHorizontal
     *
     * @dataProvider dataProvider
     * @test
     */
    public function shouldCorrectlyDetermineOrientation(Edge $edge, $expectedHorizontal)
    {
        EdgeOrientationCalculator::apply($edge);
        Assert::assertEquals($expectedHorizontal, $edge->horizontal);
    }
}
