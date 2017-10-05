<?php

namespace AmaTeam\Image\Projection\Test\Suite\Integration\Image\Adapter;

use AmaTeam\Image\Projection\API\Image\ImageFactoryInterface;
use AmaTeam\Image\Projection\API\Image\Format;
use AmaTeam\Image\Projection\Test\Support\Assert;

class TestSuite
{
    public static function run(ImageFactoryInterface $factory)
    {
        $size = 100;
        $image = $factory->create($size, $size);
        Assert::assertEquals($size, $image->getWidth());
        Assert::assertEquals($size, $image->getHeight());
        $color = $image->getColorAt(0, 0);
        Assert::assertInternalType('int', $color);
        $inverted = ($color ^ -1) & 0xFFFFFFFF;
        // since blending may be in use, all transparent colors will blend
        // with existing ones, providing non-expected result.
        $nextColor = $inverted | 0xFF;
        $image->setColorAt(0, 0, $nextColor);
        Assert::assertEquals($nextColor, $image->getColorAt(0, 0), '', 1);
        $binary = $image->getBinary(Format::PNG);
        $recovered = $factory->read($binary);
        Assert::assertEqualImages($image, $recovered);
    }
}
