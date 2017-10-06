<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Framework\Validation\Tile;

use AmaTeam\Image\Projection\Framework\Validation\Tile\TileRowValidator;
use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Framework\Validation\ValidatorInterface;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class TileRowValidatorTest extends Unit
{
    /**
     * @test
     */
    public function doesNotThrowOnValidInput()
    {
        $validator = new TileRowValidator(10);
        $input = array_fill(0, 10, null);
        $validator->validate($input);
    }

    /**
     * @test
     */
    public function throwsOnInvalidType()
    {
        $this->expectException(ValidationException::class);
        $validator = new TileRowValidator();
        $validator->validate('string');
    }

    /**
     * @test
     */
    public function callsTileValidator()
    {
        $size = 10;
        /** @var Mock|ValidatorInterface $tileValidator */
        $tileValidator = $this->createMock(ValidatorInterface::class);
        $tileValidator
            ->expects($this->exactly($size))
            ->method('validate');
        $validator = new TileRowValidator(null, false, $tileValidator);
        $input = array_fill(0, $size, null);
        $validator->validate($input);
    }

    /**
     * @test
     */
    public function doesNotThrowOnEmptyIfAllowed()
    {
        $validator = new TileRowValidator(null, true);
        $validator->validate([]);
    }

    /**
     * @test
     */
    public function throwsOnEmptyIfNotAllowed()
    {
        $this->expectException(ValidationException::class);
        $validator = new TileRowValidator(null, false);
        $validator->validate([]);
    }

    /**
     * @test
     */
    public function validatesSizeIfSet()
    {
        $this->expectException(ValidationException::class);
        $size = 10;
        $validator = new TileRowValidator($size);
        $input = array_fill(0, $size * 2, null);
        $validator->validate($input);
    }
}
