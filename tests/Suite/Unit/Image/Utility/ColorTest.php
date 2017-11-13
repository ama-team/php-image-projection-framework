<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Image\Utility;

use AmaTeam\Image\Projection\Image\Utility\Color;
use AmaTeam\Image\Projection\Test\Support\Assert;
use Codeception\Test\Unit;

class ColorTest extends Unit
{
    public function blendDataProvider()
    {
        return [
            [
                0xFF0000FF,
                0x0000FF7F,
                0x7F007FFF
            ],
            [
                0xFFFFFF7F,
                0x00000000,
                0xFFFFFF7F,
            ],
            [
                0x7F7F7FFF,
                0xFFFFFF7F,
                0xBFBFBFFF
            ]
        ];
    }

    /**
     * @param $target
     * @param $source
     * @param $expectation
     *
     * @test
     * @dataProvider blendDataProvider
     */
    public function shouldBlendAsExpected($target, $source, $expectation)
    {
        $result = Color::blend($target, $source);
        Assert::assertSameColor($expectation, $result, '', 1);
    }
}
