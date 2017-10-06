<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Type;

use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\API\Image\ImageInterface;
use AmaTeam\Image\Projection\Image\Manager;
use AmaTeam\Image\Projection\Specification;
use AmaTeam\Image\Projection\Test\Support\Assert;
use AmaTeam\Image\Projection\Tile\Position;
use AmaTeam\Image\Projection\Type\DefaultGenerator;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;
use AmaTeam\Image\Projection\Type\GenerationDetails;
use Codeception\Test\Unit;
use Iterator;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * TODO: add filters test
 */
class DefaultGeneratorTest extends Unit
{
    const SECOND_FACE = 'second';

    private $imageManager;
    private $image;
    private $mapping;
    private $target;
    private $reader;
    private $imageCreationCounter;
    private $imageQueryCounter;

    protected function _before()
    {
        $this->imageCreationCounter = 0;
        $this->imageQueryCounter = 0;
        $this->image = $this->createImage();
        $this->imageManager = $this->createImageManager($this->image);
        $this->mapping = $this->createMapping();
        $this->target = $this->createTarget();
        $this->reader = $this->createReader();
    }

    private function createImage()
    {
        /** @var Mock|ImageInterface $mock */
        $mock = $this->createMock(ImageInterface::class);
        $mock
            ->expects($this->any())
            ->method('setColorAt')
            ->willReturnCallback(function () {
                $this->imageQueryCounter++;
            });
        return $mock;
    }

    private function createReader()
    {
        /** @var ReaderInterface|Mock $mock */
        $mock = $this->createMock(ReaderInterface::class);
        $mock
            ->expects($this->any())
            ->method('getColorAt')
            ->willReturn(0xFFFFFFFF);
        return $mock;
    }

    private function createMapping()
    {
        /** @var MappingInterface|Mock $mock */
        $mock = $this->createMock(MappingInterface::class);
        $mock
            ->expects($this->any())
            ->method('getPosition')
            ->willReturn([MappingInterface::DEFAULT_FACE, 0, 0]);
        $mock
            ->expects($this->any())
            ->method('getFaces')
            ->willReturn([MappingInterface::DEFAULT_FACE, self::SECOND_FACE]);
        return $mock;
    }

    private function createTarget()
    {
        return (new Specification())
            ->setTileSize(new Box(1, 1))
            ->setLayout(new Box(1, 1))
            ->setPattern('{f}.jpg');
    }

    private function createImageManager(ImageInterface $image)
    {
        /** @var Mock|Manager $mock */
        $mock = $this->createMock(Manager::class);
        $mock
            ->expects($this->any())
            ->method('create')
            ->willReturnCallback(function () use ($image) {
                $this->imageCreationCounter++;
                return $image;
            });
        return $mock;
    }

    private function createGenerator()
    {
        $details = new GenerationDetails(
            $this->reader,
            $this->mapping,
            $this->target
        );
        return new DefaultGenerator(
            $this->imageManager,
            $details
        );
    }

    /**
     * @test
     */
    public function returnsIterator()
    {
        Assert::assertInstanceOf(Iterator::class, $this->createGenerator());
    }

    /**
     * @test
     */
    public function doesNotCreateImageUnlessRequested()
    {
        $generator = $this->createGenerator();
        Assert::assertEquals(0, $this->imageCreationCounter);
        Assert::assertInstanceOf(TileInterface::class, $generator->current());
        Assert::assertEquals(1, $this->imageCreationCounter);
    }

    /**
     * @test
     */
    public function switchesToNextFaceWhenCurrentEnds()
    {
        $generator = $this->createGenerator();
        $position = $generator->key();
        Assert::assertEquals(0, $position->getX());
        Assert::assertEquals(0, $position->getY());
        Assert::assertEquals(MappingInterface::DEFAULT_FACE, $position->getFace());
        $generator->next();
        $position = $generator->key();
        Assert::assertEquals(0, $position->getX());
        Assert::assertEquals(0, $position->getY());
        Assert::assertEquals(self::SECOND_FACE, $position->getFace());
    }

    /**
     * @test
     */
    public function conformsToIteratorInterface()
    {
        $generator = $this->createGenerator();
        $position = $generator->current()->getPosition();
        Assert::assertInstanceOf(Position::class, $position);
        Assert::assertEquals(MappingInterface::DEFAULT_FACE, $position->getFace());
        Assert::assertEquals(0, $position->getX());
        Assert::assertEquals(0, $position->getY());
        Assert::assertTrue($generator->valid());
        $generator->next();
        Assert::assertTrue($generator->valid());
        $generator->next();
        Assert::assertFalse($generator->valid());
        $generator->rewind();
        Assert::assertTrue($generator->valid());
        Assert::assertEquals($position, $generator->current()->getPosition());
    }
}
