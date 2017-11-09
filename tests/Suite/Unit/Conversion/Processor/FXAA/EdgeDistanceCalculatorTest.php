<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Conversion\Processor\FXAA;

use AmaTeam\Image\Projection\API\Image\ImageInterface;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\ColorGrid;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\Edge;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\EdgeDistanceCalculator;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\EdgeLumaCalculator;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\LumaGrid;
use AmaTeam\Image\Projection\Test\Support\Assert;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class EdgeDistanceCalculatorTest extends Unit
{
    const COLOR_GREEN = 0x00FF00FF;
    const COLOR_BLACK = 0x000000FF;

    public function dataProvider()
    {
        $variants = [
            [
                [
                    'x' => 2,
                    'y' => 1,
                    'image' => [
                        [self::COLOR_GREEN, self::COLOR_GREEN, self::COLOR_GREEN, self::COLOR_GREEN, self::COLOR_GREEN,],
                        [self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_GREEN, self::COLOR_GREEN,],
                        [self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK, self::COLOR_BLACK,],
                    ],
                    'horizontal' => true,
                    'inward' => true,
                ],
                1,
                3
            ]
        ];
        foreach ($variants as &$variant) {
            $edge = new Edge();
            $properties = array_shift($variant);
            foreach ($properties as $property => $value) {
                $edge->$property = $value;
            }
            $edge->image = $this->createImageMock($properties['image']);
            $edge->color = $this->createColorGrid($edge->image, $edge->x, $edge->y);
            $edge->luma = $this->createLumaGrid($edge->color);
            EdgeLumaCalculator::apply($edge);
            array_unshift($variant, $edge);
        }
        return array_map(function ($variant) {
            $edge = new Edge();
            $properties = array_shift($variant);
            foreach ($properties as $property => $value) {
                $edge->$property = $value;
            }
            array_unshift($variant, $edge);
            return $variant;
        }, $variants);
    }

    private function createImageMock(array $bitmap)
    {
        $height = sizeof($bitmap);
        $width = $height === 0 ? 0 : sizeof(reset($bitmap));
        /** @var ImageInterface|Mock $mock */
        $mock = $this->createMock(ImageInterface::class);
        $mock
            ->expects($this->any())
            ->method('getColorAt')
            ->willReturnCallback(function ($x, $y) use ($bitmap) {
                if (!isset($bitmap[$y])) {
                    throw new \RuntimeException("No such point: $x:$y");
                }
                $row = $bitmap[$y];
                if (!isset($row[$x])) {
                    throw new \RuntimeException("No such point: $x:$y");
                }
                return $row[$x];
            });
        $mock
            ->expects($this->any())
            ->method('getWidth')
            ->willReturn($width);
        $mock
            ->expects($this->any())
            ->method('getHeight')
            ->willReturn($height);
        return $mock;
    }

    /**
     * @param Edge $edge
     * @param $expectedForwardDistance
     * @param $expectedBackwardDistance
     *
     * @test
     * @dataProvider dataProvider
     */
    public function shouldComputeExpectedDistance(Edge $edge, $expectedForwardDistance, $expectedBackwardDistance)
    {
        EdgeDistanceCalculator::apply($edge);
        Assert::assertGreaterThanOrEqual($expectedForwardDistance, $edge->forwardDistance);
        Assert::assertGreaterThanOrEqual($expectedBackwardDistance, $edge->backwardDistance);
    }

    private function createColorGrid(ImageInterface $image, $x, $y)
    {
        $target = [];
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $target[$i * 3 + $j] = $image->getColorAt($x + $j - 1, $y + $i - 1);
            }
        }
        return ColorGrid::fromArray($target);
    }

    private function createLumaGrid(ColorGrid $color)
    {
        $grid = new LumaGrid();
        $grid->fill($color);
        return $grid;
    }
}
