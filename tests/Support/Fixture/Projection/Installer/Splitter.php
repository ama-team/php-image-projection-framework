<?php

namespace AmaTeam\Image\Projection\Test\Support\Fixture\Projection\Installer;

use AmaTeam\Image\Projection\Test\Support\Fixture\Projection\Fixture;
use AmaTeam\Image\Projection\Test\Support\Fixture\Projection\Offset;
use AmaTeam\Image\Projection\Test\Support\Structure;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Splits source image into tiles
 */
class Splitter
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param ImagineInterface $imagine
     * @param Filesystem $filesystem
     */
    public function __construct(
        ImagineInterface $imagine,
        Filesystem $filesystem
    ) {
        $this->imagine = $imagine;
        $this->filesystem = $filesystem;
    }

    /**
     * @param Fixture $fixture
     */
    public function split(Fixture $fixture)
    {
        $source = null;
        /**
         * @var string $face
         * @var Offset $offset
         */
        foreach ($fixture->getFaces() as $face => $offset) {
            $path = self::computePath($fixture, $face);
            if ($this->filesystem->exists($path)) {
                continue;
            }
            $source = $source ?: $this->imagine->open($fixture->getSource());
            $this->splitOff($source, $fixture, $face);
        }
    }

    /**
     * @param ImageInterface $source
     * @param Fixture $fixture
     * @param string $face
     */
    private function splitOff(ImageInterface $source, Fixture $fixture, $face)
    {
        $offset = $fixture->getFaces()[$face];
        $path = self::computePath($fixture, $face);
        $this->filesystem->mkdir(dirname($path));
        $tileSize = $fixture->getSize();
        $layout = $fixture->getLayout();
        $sliceWidth = $source->getSize()->getWidth() / $layout->getWidth();
        $sliceHeight = $source->getSize()->getHeight() / $layout->getHeight();
        $offsetX = (int) ($offset->getX() * $sliceWidth);
        $offsetY = (int) ($offset->getY() * $sliceHeight);
        $cropOffset = new Point($offsetX, $offsetY);
        $cropBox = new Box((int) $sliceWidth, (int) $sliceHeight);
        $resizeBox = new Box(
            (int) $tileSize->getWidth(),
            (int) $tileSize->getHeight()
        );
        $source
            ->copy()
            ->crop($cropOffset, $cropBox)
            ->resize($resizeBox)
            ->save($path, ['jpeg_quality' => 100]);
    }

    private static function computePath(Fixture $fixture, $face)
    {
        $parameters = ['face' => $face, 'x' => 0, 'y' => 0,];
        return implode(DIRECTORY_SEPARATOR, [
            Structure::getProjectRoot(),
            $fixture->getPattern()->resolve($parameters)
        ]);
    }
}
