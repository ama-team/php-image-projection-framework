<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Conversion\Processor\FXAA;

use AmaTeam\Image\Projection\Conversion\Processor\FXAA\Edge;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\EdgeDirectionCalculator;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\LumaGrid;
use AmaTeam\Image\Projection\Test\Support\Assert;
use Codeception\Test\Unit;

class EdgeDirectionCalculatorTest extends Unit
{
    public function dataProvider()
    {
        $variants = [
            [
                [
                    0, 0, 0,
                    1, 1, 1,
                    1, 1, 1,
                ],
                true, // is horizontal?
                true, // should result be 'inward'?
            ],
            [
                [
                    1, 1, 1,
                    1, 1, 1,
                    0, 0, 0,
                ],
                true,
                false,
            ],
            [
                [
                    0, 1, 1,
                    0, 1, 1,
                    0, 1, 1,
                ],
                false,
                true,
            ],
            [
                [
                    1, 1, 0,
                    1, 1, 0,
                    1, 1, 0,
                ],
                false,
                false,
            ],
        ];
        return array_map(function ($variant) {
            $edge = new Edge();
            $edge->luma = LumaGrid::fromArray($variant[0]);
            $edge->horizontal = $variant[1];
            return [$edge, $variant[2]];
        }, $variants);
    }

    /**
     * @param Edge $edge
     * @param bool $expectedInward
     *
     * @dataProvider dataProvider
     * @test
     */
    public function shouldCorrectlyDetermineDirection(Edge $edge, $expectedInward)
    {
        EdgeDirectionCalculator::apply($edge);
        Assert::assertEquals($expectedInward, $edge->inward);
    }
}
