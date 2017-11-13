<?php

namespace AmaTeam\Image\Projection\Test\Suite\Functional\Type;

use AmaTeam\Image\Projection\API\Image\ImageInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\Specification;
use AmaTeam\Image\Projection\Type\AbstractValidatingMapping;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class AbstractValidatingMappingTest extends Unit
{
    const FACES = ['alpha', 'beta'];

    /** @var Mock|AbstractValidatingMapping */
    private $mapping;

    protected function _before()
    {
        $this->mapping = $this->createPartialMock(
            AbstractValidatingMapping::class,
            ['getPosition', 'getCoordinates', 'getFaces',]
        );
        $this
            ->mapping
            ->expects($this->any())
            ->method('getFaces')
            ->willReturn(self::FACES);
    }

    /**
     * @test
     */
    public function doesNotThrowOnValidInput()
    {
        $size = 10;
        $specification = (new Specification())
            ->setTileSize(new Box($size, $size))
            ->setLayout(new Box($size, $size));
        $row = array_fill(0, $size, $this->mockTile($size, $size));
        $grid = array_fill(0, $size, $row);
        $tree = [];
        foreach (self::FACES as $face) {
            $tree[$face] = $grid;
        }
        $this->mapping->validate($tree, $specification);
    }

    /**
     * @test
     */
    public function throwsOnInconsistentFaces()
    {
        $this->expectException(ValidationException::class);
        $row = array_fill(0, 10, $this->mockTile(10, 10));
        $tree = [
            'alpha' => array_fill(0, 2, $row),
            'beta' => array_fill(0, 3, $row)
        ];
        $this->mapping->validate($tree, new Specification());
    }

    /**
     * @test
     */
    public function throwsOnInconsistentRows()
    {
        $this->expectException(ValidationException::class);
        $tree = [];
        for ($i = 0; $i < sizeof(self::FACES); $i++) {
            $face = self::FACES[$i];
            $row = array_fill(0, $i + 1, $this->mockTile(10, 10));
            $tree[$face] = array_fill(0, 10, $row);
        }
        $this->mapping->validate($tree, new Specification());
    }

    /**
     * @test
     */
    public function throwsOnInconsistentTileHeight()
    {
        $this->expectException(ValidationException::class);
        $tree = [];
        for ($i = 0; $i < sizeof(self::FACES); $i++) {
            $face = self::FACES[$i];
            $row = array_fill(0, 10, $this->mockTile(10, $i + 1));
            $tree[$face] = array_fill(0, 10, $row);
        }
        $this->mapping->validate($tree, new Specification());
    }

    /**
     * @test
     */
    public function throwsOnInconsistentTileWidth()
    {
        $this->expectException(ValidationException::class);
        $tree = [];
        for ($i = 0; $i < sizeof(self::FACES); $i++) {
            $face = self::FACES[$i];
            $row = array_fill(0, 10, $this->mockTile($i + 1, 10));
            $tree[$face] = array_fill(0, 10, $row);
        }
        $this->mapping->validate($tree, new Specification());
    }

    /**
     * @test
     */
    public function throwsOnUnexpectedTileHeight()
    {
        $this->expectException(ValidationException::class);
        $size = 10;
        $specification = (new Specification())
            ->setTileSize(new Box($size, $size + 1));
        $tree = [];
        for ($i = 0; $i < sizeof(self::FACES); $i++) {
            $face = self::FACES[$i];
            $row = array_fill(0, 10, $this->mockTile($size, $size));
            $tree[$face] = array_fill(0, 10, $row);
        }
        $this->mapping->validate($tree, $specification);
    }

    /**
     * @test
     */
    public function throwsOnUnexpectedTileWidth()
    {
        $this->expectException(ValidationException::class);
        $size = 10;
        $specification = (new Specification())
            ->setTileSize(new Box($size + 1, $size));
        $tree = [];
        for ($i = 0; $i < sizeof(self::FACES); $i++) {
            $face = self::FACES[$i];
            $row = array_fill(0, 10, $this->mockTile($size, $size));
            $tree[$face] = array_fill(0, 10, $row);
        }
        $this->mapping->validate($tree, $specification);
    }

    /**
     * @test
     */
    public function throwsOnUnexpectedFaceHeight()
    {
        $this->expectException(ValidationException::class);
        $size = 10;
        $specification = (new Specification())
            ->setLayout(new Box($size, $size + 1));
        $tree = [];
        for ($i = 0; $i < sizeof(self::FACES); $i++) {
            $face = self::FACES[$i];
            $row = array_fill(0, $size, $this->mockTile(10, 10));
            $tree[$face] = array_fill(0, $size, $row);
        }
        $this->mapping->validate($tree, $specification);
    }

    /**
     * @test
     */
    public function throwsOnUnexpectedFaceWidth()
    {
        $this->expectException(ValidationException::class);
        $size = 10;
        $specification = (new Specification())
            ->setLayout(new Box($size + 1, $size));
        $tree = [];
        for ($i = 0; $i < sizeof(self::FACES); $i++) {
            $face = self::FACES[$i];
            $row = array_fill(0, $size, $this->mockTile(10, 10));
            $tree[$face] = array_fill(0, $size, $row);
        }
        $this->mapping->validate($tree, $specification);
    }

    private function mockTile($width, $height)
    {
        $methods = [
            'getWidth' => $width,
            'getHeight' => $height
        ];
        /** @var ImageInterface|Mock $image */
        $image = $this->createConfiguredMock(ImageInterface::class, $methods);
        /** @var TileInterface|Mock $tile */
        $tileMethods = ['getImage' => $image];
        $tile = $this->createConfiguredMock(TileInterface::class, $tileMethods);
        return $tile;
    }
}
