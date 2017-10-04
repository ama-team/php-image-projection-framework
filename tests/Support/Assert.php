<?php

namespace AmaTeam\Image\Projection\Test\Support;

use AmaTeam\Image\Projection\Image\Adapter\ImageInterface;
use AmaTeam\Image\Projection\Image\Format;
use Imagick;

class Assert extends \PHPUnit\Framework\Assert
{
    /**
     * @param ImageInterface $expected
     * @param ImageInterface $actual
     */
    public static function assertEqualImages(
        ImageInterface $expected,
        ImageInterface $actual
    ) {
        Assert::assertEquals($expected->getWidth(), $actual->getWidth());
        Assert::assertEquals($expected->getHeight(), $actual->getHeight());
        /** @var ImageInterface $image */
        foreach ([$expected, $actual] as $image) {
            $resource = $image->getResource();
            if ($resource instanceof Imagick) {
                $resource->stripImage();
            }
        }
        Assert::assertEquals(
            $expected->getBinary(Format::PNG),
            $expected->getBinary(Format::PNG)
        );
    }

    /**
     * @param int $expected
     * @param int $actual
     * @param string $message
     * @param int|float $deviation
     */
    public static function assertSameColor(
        $expected,
        $actual,
        $message = '',
        $deviation = 0
    ) {
        $message = $message ?: 'Colors differ';
        $equality = self::sameColor($expected, $actual, $deviation);
        self::assertTrue($equality, $message);
    }

    /**
     * @param int $expected
     * @param int $actual
     * @param int $deviation
     * @return bool
     */
    public static function sameColor($expected, $actual, $deviation = 0)
    {
        $components = ['red', 'green', 'blue', 'alpha'];
        for ($i = 0; $i < sizeof($components); $i++) {
            $shift = 3 - $i;
            $expectedChannel = ($expected >> $shift) & 0xFF;
            $actualChannel = ($actual >> $shift) & 0xFF;
            if (abs($expectedChannel - $actualChannel) > $deviation) {
                return false;
            }
        }
        return true;
    }
}
