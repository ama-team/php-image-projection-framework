<?php

namespace AmaTeam\Image\Projection\Image\Adapter\Imagick;

use AmaTeam\Image\Projection\API\Image\ImageInterface;
use AmaTeam\Image\Projection\Image\EncodingOptions;
use Imagick;
use ImagickDraw;

class Image implements ImageInterface
{
    /**
     * @var Imagick
     */
    private $resource;
    /**
     * @var int
     */
    private $width;
    /**
     * @var int
     */
    private $height;

    /**
     * @param Imagick $resource
     */
    public function __construct(Imagick $resource)
    {
        $this->resource = $resource;
        $this->width = $resource->getImageWidth();
        $this->height = $resource->getImageHeight();
    }

    /**
     * @inheritdoc
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @inheritdoc
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @inheritdoc
     */
    public function getColorAt($x, $y)
    {
        return Color::get($this->resource->getImagePixelColor($x, $y));
    }

    /**
     * @inheritdoc
     */
    public function setColorAt($x, $y, $color)
    {
        $pixel = $this->resource->getImagePixelColor($x, $y);
        Color::set($pixel, $color);
        $drawer = new ImagickDraw();
        $drawer->setFillColor($pixel);
        $drawer->point($x, $y);
        $this->resource->drawImage($drawer);
    }

    /**
     * @inheritdoc
     */
    public function getBinary($format, EncodingOptions $options = null)
    {
        $options = $options ?: EncodingOptions::defaults();
        $quality = $options->getQuality();
        $compression = $options->getCompression();
        $this->resource->setImageFormat($format);
        $this->resource->setImageCompression((int) ($compression * 10));
        $this->resource->setImageCompressionQuality((int) ($quality * 100));
        return $this->resource->getImageBlob();
    }

    /**
     * @inheritDoc
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @inheritDoc
     */
    public function __destruct()
    {
        $this->resource->clear();
    }
}
