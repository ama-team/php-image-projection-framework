<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Framework\Validation\Tile;

use AmaTeam\Image\Projection\Framework\Validation\Tile\TileTreeValidator;
use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Framework\Validation\ValidatorInterface;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class TileTreeValidatorTest extends Unit
{
    const FACES = ['default'];
    const DEFAULT_INPUT = ['default' => []];

    /**
     * @test
     */
    public function doesNotThrowOnValidInput()
    {
        $validator = new TileTreeValidator(self::FACES);
        $validator->validate(self::DEFAULT_INPUT);
    }

    /**
     * @test
     */
    public function throwsOnInvalidType()
    {
        $this->expectException(ValidationException::class);
        $validator = new TileTreeValidator(self::FACES);
        $validator->validate(null);
    }

    /**
     * @test
     */
    public function throwsOnMissingFace()
    {
        $this->expectException(ValidationException::class);
        $validator = new TileTreeValidator(self::FACES);
        $validator->validate([]);
    }

    /**
     * @test
     */
    public function throwsOnExtraFace()
    {
        $this->expectException(ValidationException::class);
        $validator = new TileTreeValidator(self::FACES);
        $input = self::DEFAULT_INPUT;
        $input['extra-face'] = [];
        $validator->validate($input);
    }

    /**
     * @test
     */
    public function callsChildValidator()
    {
        /** @var ValidatorInterface|Mock $child */
        $child = $this->createMock(ValidatorInterface::class);
        $child
            ->expects($this->exactly(1))
            ->method('validate');
        $validator = new TileTreeValidator(self::FACES, $child);
        $validator->validate(self::DEFAULT_INPUT);
    }
}
