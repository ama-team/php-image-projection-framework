<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Framework\Validation\Tile;

use AmaTeam\Image\Projection\API\Image\ImageInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\Framework\Validation\Tile\SingleTileValidator;
use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Geometry\Box;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class SingleTileValidatorTest extends Unit
{
    /**
     * @test
     */
    public function doesNotThrowOnValidInput()
    {
        $size = 10;
        $validator = new SingleTileValidator(new Box($size, $size));
        $mock = $this->mock($size, $size);
        $validator->validate($mock);
    }

    /**
     * @test
     */
    public function validatesType()
    {
        $this->expectException(ValidationException::class);
        $validator = new SingleTileValidator();
        $validator->validate('value');
    }

    /**
     * @test
     */
    public function validatesWidth()
    {
        $this->expectException(ValidationException::class);
        $size = 10;
        $width = 1;
        $tile = $this->mock($width, $size);
        $validator = new SingleTileValidator(new Box($size, $size));
        $validator->validate($tile);
    }

    /**
     * @test
     */
    public function validatesHeight()
    {
        $this->expectException(ValidationException::class);
        $size = 10;
        $height = 1;
        $tile = $this->mock($size, $height);
        $validator = new SingleTileValidator(new Box($size, $size));
        $validator->validate($tile);
    }

    /**
     * @test
     */
    public function doesNotValidateSizeIfNotSpecified()
    {
        $validator = new SingleTileValidator();
        $tile = $this->mock(1, 10);
        $validator->validate($tile);
    }

    private function mock($width, $height)
    {
        /** @var Mock|ImageInterface $image */
        $image = $this->createMock(ImageInterface::class);
        $image
            ->expects($this->any())
            ->method('getHeight')
            ->willReturn($height);
        $image
            ->expects($this->any())
            ->method('getWidth')
            ->willReturn($width);
        /** @var TileInterface|Mock $mock */
        $mock = $this->createMock(TileInterface::class);
        $mock
            ->expects($this->any())
            ->method('getImage')
            ->willReturn($image);
        return $mock;
    }
}
