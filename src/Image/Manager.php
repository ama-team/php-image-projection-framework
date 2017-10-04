<?php

namespace AmaTeam\Image\Projection\Image;

use AmaTeam\Image\Projection\Image\Adapter\ImageFactoryInterface;
use AmaTeam\Image\Projection\Image\Adapter\ImageInterface;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;

class Manager
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var ImageFactoryInterface
     */
    private $imageFactory;

    /**
     * @param FilesystemInterface $filesystem
     * @param ImageFactoryInterface $imageFactory
     */
    public function __construct(
        FilesystemInterface $filesystem,
        ImageFactoryInterface $imageFactory
    ) {
        $this->filesystem = $filesystem;
        $this->imageFactory = $imageFactory;
    }

    /**
     * @param int $width
     * @param int $height
     * @return ImageInterface
     */
    public function create($width, $height)
    {
        return $this->imageFactory->create($width, $height);
    }

    /**
     * @param string $path
     * @return ImageInterface
     */
    public function read($path)
    {
        if (!$this->filesystem->has($path)) {
            $message = "Couldn\'t find image at $path";
            throw new BadMethodCallException($message);
        }
        $content = $this->filesystem->read($path);
        return $this->imageFactory->read($content);
    }

    /**
     * @param ImageInterface $image
     * @param string $path
     * @param string $format
     * @param EncodingOptions $options
     */
    public function save(
        ImageInterface $image,
        $path,
        $format,
        EncodingOptions $options = null
    ) {
        $blob = $image->getBinary($format, $options);
        if ($this->filesystem->has($path)) {
            $this->filesystem->delete($path);
        }
        $this->filesystem->write($path, $blob);
    }
}
