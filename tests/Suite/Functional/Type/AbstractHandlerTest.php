<?php

namespace AmaTeam\Image\Projection\Test\Suite\Unit\Type;

use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\API\Image\ImageInterface;
use AmaTeam\Image\Projection\Image\Manager;
use AmaTeam\Image\Projection\Specification;
use AmaTeam\Image\Projection\Test\Support\Assert;
use AmaTeam\Image\Projection\Test\Support\Dummy\HandlerDummy;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;
use Codeception\Test\Unit;
use League\Flysystem\FilesystemInterface;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class AbstractHandlerTest extends Unit
{
    private function createHandler(
        FilesystemInterface $filesystem = null,
        Manager $manager = null,
        MappingInterface $mapping = null
    ) {
        $mapping = $mapping ?: $this->createMock(MappingInterface::class);
        if (!$filesystem) {
            /** @var FilesystemInterface|Mock $filesystem */
            $filesystem = $this->createMock(FilesystemInterface::class);
            $filesystem
                ->expects($this->any())
                ->method('listContents')
                ->willReturn([]);
        }
        if (!$manager) {
            /** @var ImageInterface|Mock $image */
            $image = $this->createMock(ImageInterface::class);
            $image->expects($this->any())->method('getWidth')->willReturn(0);
            $image->expects($this->any())->method('getHeight')->willReturn(0);
            /** @var Manager|Mock $manager */
            $manager = $this->createMock(Manager::class);
            $manager->expects($this->any())->method('create')->willReturn($image);
            $manager->expects($this->any())->method('read')->willReturn($image);
        }
        return new HandlerDummy($filesystem, $manager, $mapping);
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     */
    public function readFailsOnEmptyResult()
    {
        $specification = (new Specification())->setPattern('');
        return $this->createHandler()->read($specification);
    }

    // TODO: test that will assure specification sizes are computed if not
    // present

    /**
     * @test
     * @expectedException \BadMethodCallException
     */
    public function convertFailsIfTargetDoesNotSpecifySize()
    {
        $handler = $this->createHandler();
        $target = new Specification();
        $reader = $this->createMock(ReaderInterface::class);
        return $handler->createGenerator($reader, $target);
    }
}
