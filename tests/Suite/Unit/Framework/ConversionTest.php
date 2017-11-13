<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Framework;

use AmaTeam\Image\Projection\API\Conversion\ListenerInterface;
use AmaTeam\Image\Projection\API\Conversion\ProcessorInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\API\Type\GeneratorInterface;
use AmaTeam\Image\Projection\Framework\Conversion;
use AmaTeam\Image\Projection\Test\Support\Assert;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class ConversionTest extends Unit
{
    /**
     * @test
     */
    public function recognizesProcessorOutput()
    {
        /** @var TileInterface|Mock $tile */
        $tile = $this->createMock(TileInterface::class);
        /** @var GeneratorInterface|Mock $generator */
        $generator = $this->createMock(GeneratorInterface::class);
        $generator
            ->expects($this->any())
            ->method('current')
            ->willReturn($tile);
        $generator
            ->expects($this->any())
            ->method('valid')
            ->willReturn(true, false);
        /** @var TileInterface|Mock $replacement */
        $replacement = $this->createMock(TileInterface::class);
        /** @var ProcessorInterface|Mock $processor */
        $processor = $this->createMock(ProcessorInterface::class);
        $processor
            ->expects($this->any())
            ->method('process')
            ->willReturn($replacement);
        $capture = null;
        /** @var ListenerInterface|Mock $listener */
        $listener = $this->createMock(ListenerInterface::class);
        $listener
            ->expects($this->once())
            ->method('accept')
            ->willReturnCallback(function ($tile) use (&$capture) {
                $capture = $tile;
            });
        $specification = $this->createMock(SpecificationInterface::class);
        (new Conversion($specification, $generator))
            ->addProcessor($processor)
            ->addListener($listener)
            ->run();
        Assert::assertSame($replacement, $capture);
    }
}
