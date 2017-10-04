<?php

namespace AmaTeam\Image\Projection\Test\Suite\Integration\Image;

use AmaTeam\Image\Projection\Image\Adapter\ImageFactoryInterface;
use AmaTeam\Image\Projection\Image\Adapter\Gd\ImageFactory as GdFactory;
use AmaTeam\Image\Projection\Image\Adapter\ImageInterface;
use AmaTeam\Image\Projection\Image\Adapter\Imagick\ImageFactory as ImagickFactory;
use AmaTeam\Image\Projection\Image\Format;
use AmaTeam\Image\Projection\Image\Manager;
use AmaTeam\Image\Projection\Test\Support\Assert;
use Codeception\Test\Unit;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Vfs\VfsAdapter;
use VirtualFileSystem\FileSystem;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class ManagerTest extends Unit
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    protected function _before()
    {
        $adapter = new VfsAdapter(new FileSystem());
        $this->filesystem = new Flysystem($adapter);
    }

    public function dataProvider()
    {
        return [
            [new GdFactory()],
            [new ImagickFactory()],
        ];
    }

    /**
     * @param ImageFactoryInterface $factory
     *
     * @dataProvider dataProvider
     */
    public function testReadAndSave(ImageFactoryInterface $factory)
    {
        $manager = new Manager($this->filesystem, $factory);
        $path = 'test.png';
        $image = $manager->create(100, 100);
        $manager->save($image, $path, Format::PNG);
        $secondImage = $manager->read($path);
        Assert::assertEqualImages($image, $secondImage);
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     */
    public function testMissingFileRead()
    {
        $factory = $this->createMock(ImageFactoryInterface::class);
        $manager = new Manager($this->filesystem, $factory);
        $manager->read('test.png');
    }

    /**
     * @test
     */
    public function saveOverwritesExistingFile()
    {
        $path = 'test.png';
        $this->filesystem->put($path, '');
        $binary = 'binary';
        /** @var ImageInterface|Mock $image */
        $image = $this->createMock(ImageInterface::class);
        $image->expects($this->any())->method('getBinary')->willReturn($binary);
        /** @var ImageFactoryInterface|Mock $factory */
        $factory = $this->createMock(ImageFactoryInterface::class);
        $manager = new Manager($this->filesystem, $factory);
        $manager->save($image, $path, Format::PNG);
        Assert::assertEquals($binary, $this->filesystem->read($path));
    }
}
