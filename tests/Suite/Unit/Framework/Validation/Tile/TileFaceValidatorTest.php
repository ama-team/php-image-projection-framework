<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Framework\Validation\Tile;

use AmaTeam\Image\Projection\Framework\Validation\Tile\TileFaceValidator;
use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Framework\Validation\ValidatorInterface;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class TileFaceValidatorTest extends Unit
{
    /**
     * @test
     */
    public function doesNotThrowOnValidInput()
    {
        $validator = new TileFaceValidator(null, false);
        $input = array_fill(0, 10, null);
        $validator->validate($input);
    }

    /**
     * @test
     */
    public function throwsOnInvalidType()
    {
        $this->expectException(ValidationException::class);
        $validator = new TileFaceValidator();
        $validator->validate('string');
    }

    /**
     * @test
     */
    public function throwsOnInvalidSize()
    {
        $this->expectException(ValidationException::class);
        $size = 10;
        $validator = new TileFaceValidator($size);
        $input = array_fill(0, $size * 2, null);
        $validator->validate($input);
    }

    /**
     * @test
     */
    public function throwsOnEmptyIfNotAllowed()
    {
        $this->expectException(ValidationException::class);
        $validator = new TileFaceValidator(null, false);
        $validator->validate([]);
    }

    /**
     * @test
     */
    public function doesNotThrowOnEmptyIfAllowed()
    {
        $validator = new TileFaceValidator(null, true);
        $validator->validate([]);
    }

    /**
     * @test
     */
    public function callsChildValidator()
    {
        $size = 10;
        /** @var Mock|ValidatorInterface $child */
        $child = $this->createMock(ValidatorInterface::class);
        $child
            ->expects($this->exactly($size))
            ->method('validate');
        $validator = new TileFaceValidator(null, false, $child);
        $input = array_fill(0, $size, null);
        $validator->validate($input);
    }
}
