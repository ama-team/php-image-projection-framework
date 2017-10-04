<?php
namespace AmaTeam\Image\Projection\Test\Unit\Filesystem;

use AmaTeam\Image\Projection\Filesystem\Pattern;
use PHPUnit\Framework\Assert;

class PatternTest extends \Codeception\Test\Unit
{
    public function dataProvider() {
        $examples = [
            [
                '{f}/{x}-{y}.jpg',
                [
                    'photo.jpg' => null,
                    'photo/1-0.jpg' => ['f' => 'photo', 'x' => '1', 'y' => '0']
                ]
            ]
        ];
        return array_reduce($examples, function ($carrier, $entry) {
            foreach ($entry[1] as $path => $parameters) {
                $carrier[] = [$entry[0], $path, $parameters];
            }
            return $carrier;
        }, []);
    }

    /**
     * @param $pattern
     * @param $path
     * @param $parameters
     *
     * @dataProvider dataProvider
     */
    public function testCommonVariant($pattern, $path, $parameters) {
        $pattern = new Pattern($pattern);
        if ($parameters === null) {
            Assert::assertFalse($pattern->matches($path));
            return;
        }
        Assert::assertTrue($pattern->matches($path));
        Assert::assertEquals($pattern->getParameters($path), $parameters);
    }

    public function resolutionDataProvider()
    {
        return [
            [
                'prefix/{param}/suffix',
                ['param' => 'value'],
                'prefix/value/suffix'
            ],
            [
                '{param}/{varam}/{haram}',
                ['param' => 'value', 'varam' => 'zalue', 'zaram' => 'palue'],
                'value/zalue/{haram}'
            ]
        ];
    }

    /**
     * @test
     *
     * @dataProvider resolutionDataProvider
     */
    public function resolvesToNewPattern($input, $parameters, $expectation)
    {
        $pattern = new Pattern($input);
        $result = $pattern->resolve($parameters);
        Assert::assertEquals($expectation, $result->getSource());
    }

    /**
     * @test
     */
    public function doesNotRecognizeSpecialCharacterParameter()
    {
        $pattern = new Pattern('test/{#}/test');
        Assert::assertFalse($pattern->matches('test/1/test'));
    }

    /**
     * @test
     */
    public function correctlyReturnsFixedPart()
    {
        $source = '//alpha//beta//gamma-{param}/theta';
        $expectation = 'alpha/beta';
        $pattern = new Pattern($source);
        Assert::assertEquals($expectation, $pattern->getFixedPart());
    }

    /**
     * @test
     */
    public function returnsEmptyParametersForNonMatchingInput()
    {
        $input = '{param}/suffix';
        $pattern = new Pattern($input);
        Assert::assertEquals([], $pattern->getParameters('value/'));
    }

    /**
     * @test
     */
    public function returnsSourceString()
    {
        $input = 'prefix/{param}/suffix';
        $pattern = new Pattern($input);
        Assert::assertEquals($input, $pattern->getSource());
    }

    /**
     * @test
     */
    public function convertsToStringAsSourceString()
    {
        $input = 'prefix/{param}/suffix';
        Assert::assertEquals($input, (string) new Pattern($input));
    }
}
