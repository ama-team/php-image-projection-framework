<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Image\Utility;

use AmaTeam\Image\Projection\Image\Utility\Luma;
use AmaTeam\Image\Projection\Test\Support\Assert;
use Codeception\Test\Unit;

class LumaTest extends Unit
{
    public function dataProvider()
    {
        return [
            [0xFF0000FF, 0.2126],
            [0x00FF00FF, 0.7152],
            [0x0000FFFF, 0.0722],
            [0x7F7F7FFF, 0.5]
        ];
    }

    /**
     * @param $color
     * @param $expectation
     *
     * @dataProvider dataProvider
     * @test
     */
    public function shouldCalculateExpectedValue($color, $expectation)
    {
        Assert::assertEquals($expectation, Luma::compute($color), '', 0.02);
    }
}
