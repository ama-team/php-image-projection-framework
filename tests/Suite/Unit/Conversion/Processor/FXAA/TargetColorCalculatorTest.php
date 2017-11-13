<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Conversion\Processor\FXAA;

use AmaTeam\Image\Projection\Conversion\Processor\FXAA\ColorGrid;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\Edge;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\TargetColorCalculator;
use AmaTeam\Image\Projection\Test\Support\Assert;
use Codeception\Test\Unit;

class TargetColorCalculatorTest extends Unit
{
    const COLOR_RED = 0xFF0000FF;
    const COLOR_BLUE = 0x00FF00FF;
    const COLOR_GREEN = 0x0000FFFF;
    const COLOR_BLACK = 0x000000FF;

    public function dataProvider()
    {
        $variants = [
            [
                [
                    self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK,
                    self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK,
                    self::COLOR_RED, self::COLOR_RED, self::COLOR_RED,
                ],
                true, // is horizontal?
                false, // is inward?
                0.0,
                self::COLOR_BLACK,
            ],
            [
                [
                    self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK,
                    self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK,
                    self::COLOR_RED, self::COLOR_RED, self::COLOR_RED,
                ],
                true,
                false,
                0.5,
                0x7F0000FF
            ],
            [
                [
                    self::COLOR_RED, self::COLOR_RED, self::COLOR_RED,
                    self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK,
                    self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK,
                ],
                true,
                true,
                0.5,
                0x7F0000FF
            ],
            [
                [
                    self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_RED,
                    self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_RED,
                    self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_RED,
                ],
                false,
                false,
                0.5,
                0x7F0000FF
            ],
            [
                [
                    self::COLOR_RED, self::COLOR_BLACK, self::COLOR_BLACK,
                    self::COLOR_RED, self::COLOR_BLACK, self::COLOR_BLACK,
                    self::COLOR_RED, self::COLOR_BLACK, self::COLOR_BLACK,
                ],
                false,
                true,
                0.5,
                0x7F0000FF
            ],
        ];
        return array_map(function ($variant) {
            $edge = new Edge();
            $edge->color = ColorGrid::fromArray($variant[0]);
            $edge->horizontal = $variant[1];
            $edge->inward = $variant[2];
            return [$edge, $variant[3], $variant[4]];
        }, $variants);
    }

    /**
     * @param Edge $edge
     * @param float $offset
     * @param int $expectation
     *
     * @dataProvider dataProvider
     * @test
     */
    public function shouldCalculateExpectedValue(Edge $edge, $offset, $expectation)
    {
        $color = TargetColorCalculator::calculate($edge, $offset);
        Assert::assertSameColor($expectation, $color, '', 1);
    }
}
