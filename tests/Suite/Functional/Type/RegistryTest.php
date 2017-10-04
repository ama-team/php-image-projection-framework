<?php

namespace AmaTeam\Image\Projection\Test\Suite\Functional\Type;

use AmaTeam\Image\Projection\Image\Adapter\ImageFactoryInterface;
use AmaTeam\Image\Projection\Test\Support\Assert;
use AmaTeam\Image\Projection\Type\Equirectangular\Handler;
use AmaTeam\Image\Projection\Type\Registry;
use Codeception\Test\Unit;
use League\Flysystem\FilesystemInterface;

class RegistryTest extends Unit
{
    /**
     * @test
     * @expectedException \BadMethodCallException
     */
    public function throwsOnMissingHandler()
    {
        $this->createRegistry()->getHandler('Missing');
    }

    /**
     * @test
     */
    public function registersAndReturnsBundledHandler()
    {
        $handler = $this
            ->createRegistry()
            ->registerDefaultTypes()
            ->getHandler(Handler::TYPE);
        Assert::assertInstanceOf(Handler::class, $handler);
    }

    private function createRegistry()
    {
        return new Registry(
            $this->createMock(FilesystemInterface::class),
            $this->createMock(ImageFactoryInterface::class)
        );
    }
}
