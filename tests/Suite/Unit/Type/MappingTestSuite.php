<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Type;

use AmaTeam\Image\Projection\Test\Support\Assert;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use Codeception\Test\Unit;

abstract class MappingTestSuite extends Unit
{
    /**
     * Returns examples in following format:
     *
     * [
     *   [mapping construction parameters],
     *   [latitude:float, longitude: float],
     *   [face:string|int, u: int, v:int]
     * ]
     *
     * @return array
     */
    abstract public function dataProvider();

    /**
     * @param array $parameters
     * @return MappingInterface
     */
    abstract protected function createMapping(array $parameters);

    /**
     * @param array $parameters
     * @param array $coordinates
     * @param array $position
     *
     * @dataProvider dataProvider
     */
    public function testConversion(
        array $parameters,
        array $coordinates,
        array $position
    ) {
        $mapping = $this->createMapping($parameters);
        $computed = $mapping->getPosition($coordinates[0], $coordinates[1]);
        self::assertEqualArrays($position, $computed, 1);
        $computed = $mapping->getCoordinates(
            $position[0],
            $position[1],
            $position[2]
        );
        self::assertEqualArrays($coordinates, $computed, 0.01);
    }

    private static function assertEqualArrays(array $expected, array $actual, $diff = 0.0)
    {
        Assert::assertSameSize($expected, $actual);
        foreach (array_keys($expected) as $key) {
            Assert::assertArrayHasKey($key, $actual);
            Assert::assertEquals($expected[$key], $actual[$key], '', $diff);
        }
    }
}
