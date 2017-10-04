<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Type;

use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\Image\Adapter\ImageInterface;
use AmaTeam\Image\Projection\Test\Support\Assert;
use AmaTeam\Image\Projection\Tile\Tile;
use AmaTeam\Image\Projection\Type\SizeExtractor;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class SizeExtractorTest extends Unit
{
    private function createImageMock($width, $height)
    {
        /** @var Mock $mock */
        $mock = $this->createMock(ImageInterface::class);
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

    private function createTileSet($columns, $rows, $width = 1, $height = 1)
    {
        $result = [];
        for ($y = 0; $y < $rows; $y++) {
            $row = [];
            for ($x = 0; $x < $columns; $x++) {
                /** @var Mock $mock */
                $mock = $this->createMock(Tile::class);
                $mock
                    ->expects($this->any())
                    ->method('getImage')
                    ->willReturn($this->createImageMock($width, $height));
                $row[] = $mock;
            }
            $result[] = $row;
        }
        return $result;
    }

    public function layoutExampleProvider()
    {
        return [
            [
                $this->createTileSet(0, 0),
                new Box(0, 0)
            ],
            [
                $this->createTileSet(1, 1),
                new Box(1, 1)
            ],
            [
                $this->createTileSet(0, 10),
                new Box(0, 10)
            ],
            [
                $this->createTileSet(10, 0),
                new Box(0, 0)
            ],
        ];
    }

    /**
     * @param $tileSet
     * @param $expectation
     *
     * @dataProvider layoutExampleProvider
     */
    public function testLayoutCreation($tileSet, $expectation)
    {
        Assert::assertEquals($expectation, SizeExtractor::calculateLayout($tileSet));
    }

    public function tileSizeExampleProvider()
    {
        return [
            [
                $this->createTileSet(0, 0),
                null
            ],
            [
                $this->createTileSet(0, 1),
                null
            ],
            [
                $this->createTileSet(1, 0),
                null
            ],
            [
                $this->createTileSet(1, 1),
                new Box(1, 1)
            ],
            [
                $this->createTileSet(1, 1),
                new Box(1, 1)
            ],
            [
                $this->createTileSet(10, 10),
                new Box(1, 1)
            ]
        ];
    }

    /**
     * @param $tileSet
     * @param $expectation
     *
     * @dataProvider tileSizeExampleProvider
     */
    public function testTileSizeExtraction($tileSet, $expectation)
    {
        Assert::assertEquals($expectation, SizeExtractor::extractTileSize($tileSet));
    }

    public function sizeExampleProvider()
    {
        return [
            [
                $this->createTileSet(0, 0),
                null
            ],
            [
                $this->createTileSet(0, 1),
                null
            ],
            [
                $this->createTileSet(1, 0),
                null
            ],
            [
                $this->createTileSet(1, 1, 10, 10),
                new Box(10, 10)
            ],
            [
                $this->createTileSet(5, 20, 5, 20),
                new Box(25, 400)
            ]
        ];
    }

    /**
     * @param $tileSet
     * @param $expectation
     *
     * @dataProvider sizeExampleProvider
     */
    public function testSizeExtraction($tileSet, $expectation)
    {
        Assert::assertEquals($expectation, SizeExtractor::extractSize($tileSet));
    }
}
