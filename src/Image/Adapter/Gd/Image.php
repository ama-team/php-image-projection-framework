<?php

namespace AmaTeam\Image\Projection\Image\Adapter\Gd;

use AmaTeam\Image\Projection\Image\Adapter\ImageInterface;
use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\Image\Format;

class Image implements ImageInterface
{
    /**
     * @var resource
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
     * @param resource $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
        $this->width = imagesx($resource);
        $this->height = imagesy($resource);
    }

    /**
     * @inheritDoc
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @inheritDoc
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @inheritDoc
     */
    public function getColorAt($x, $y)
    {
        $color = imagecolorat($this->resource, $x, $y);
        // shifting 23 is like shifting 24 but multiplied by 2, so 127
        // converts to 254
        return (($color & 0xFFFFFF) << 8) | ((~$color >> 23) & 0xFF);
    }

    /**
     * @inheritDoc
     */
    public function setColorAt($x, $y, $color)
    {
        // setting last alpha channel bit to 0, so alpha looks like that:
        // ?? ?? ?? ?0
        // then shifting it 23 bits left, effectively dividing it by 2
        $encoded = ($color >> 8) | ((~$color & 0xFE)) << 23;
        imagesetpixel($this->resource, $x, $y, $encoded);
    }

    /**
     * @inheritDoc
     */
    public function getBinary($format, EncodingOptions $options = null)
    {
        $options = $options ?: EncodingOptions::defaults();
        switch ($format) {
            case Format::JPEG:
                $quality = (int) ($options->getQuality() * 100);
                ob_start();
                imagejpeg($this->resource, null, $quality);
                return ob_get_clean();
            case Format::PNG:
                $compression = (int) ($options->getCompression() * 9);
                ob_start();
                imagepng($this->resource, null, $compression);
                return ob_get_clean();
            default:
                $message = "Unknown image format $format";
                throw new \BadMethodCallException($message);
        }
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
        imagedestroy($this->resource);
    }
}
