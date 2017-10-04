<?php
namespace AmaTeam\Image\Projection\Test\Unit\Filesystem\Pattern;

use AmaTeam\Image\Projection\Filesystem\Pattern\Chunk;
use Codeception\Test\Unit;
use PHPUnit\Framework\Assert;

class ChunkTest extends Unit
{

    public function dataProvider()
    {
        $input = [
            [
                'exact-match',
                Chunk::TYPE_EXACT_MATCH,
                [
                    'invalid' => null,
                    'exact-match' => []
                ]
            ],
            [
                'simple-param-{param}',
                Chunk::TYPE_EXPRESSION,
                [
                    'simple-param-value' => ['param' => 'value'],
                    'simple-param-01' => ['param' => '01'],
                    'simple-param-#' => null
                ]
            ],
            [
                'repetitive-param-{param}-{param}',
                Chunk::TYPE_EXPRESSION,
                [
                    'repetitive-param-01-02' => ['param' => '01']
                ]
            ]
        ];
        return array_reduce($input, function ($carrier, $entry) {
            foreach ($entry[2] as $segment => $params) {
                $carrier[] = [$entry[0], $entry[1], $segment, $params];
            }
            return $carrier;
        }, []);
    }

    /**
     * @param $chunk
     * @param $type
     * @param $segment
     * @param $parameters
     *
     * @dataProvider dataProvider
     */
    public function testCommonVariant($chunk, $type, $segment, $parameters)
    {
        $chunk = new Chunk($chunk);
        Assert::assertEquals($type, $chunk->getType());
        if ($parameters === null) {
            Assert::assertFalse($chunk->matches($segment));
            return;
        }
        Assert::assertEquals($parameters, $chunk->getParameters($segment));
    }

    /**
     * @test
     */
    public function fallsBackToExactTypeIfNoParametersFound()
    {
        $source = 'prefix/{invalid param}/suffix';
        $chunk = new Chunk($source);
        Assert::assertEquals(Chunk::TYPE_EXACT_MATCH, $chunk->getType());
        Assert::assertEquals($source, $chunk->getExpression());
    }

    /**
     * @test
     */
    public function returnsEmptyParametersOnNonMatchingValue()
    {
        $chunk = new Chunk('{param}-suffix');
        Assert::assertEquals([], $chunk->getParameters('value'));
    }

    public function resolutionParameterProvider()
    {
        return [
            [
                '{param}',
                ['param' => 'value'],
                'value'
            ],
            [
                '{param}-{param}',
                ['param' => 'value'],
                'value-value'
            ],
        ];
    }

    /**
     * @param $input
     * @param $params
     * @param $expectancy
     *
     * @test
     *
     * @dataProvider resolutionParameterProvider
     */
    public function resolvesWithProvidedParameters($input, $params, $expectancy)
    {
        $chunk = new Chunk($input);
        $chunk = $chunk->resolve($params);
        Assert::assertEquals($expectancy, $chunk->getSource());
    }

    /**
     * @test
     */
    public function convertsToString()
    {
        $input = '{param}';
        Assert::assertEquals($input, (string) new Chunk($input));
    }
}