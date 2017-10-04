<?php

namespace AmaTeam\Image\Projection\Image;

class EncodingOptions
{
    const DEFAULT_COMPRESSION = 1.0;
    const DEFAULT_QUALITY = 1.0;
    /**
     * Compression level, from 0 for none to 1 for maximum possible
     *
     * @var float
     */
    private $compression = self::DEFAULT_COMPRESSION;
    /**
     * Quality level, from 0 for minimum to 1 for maximum
     *
     * @var float
     */
    private $quality = self::DEFAULT_QUALITY;

    /**
     * @var EncodingOptions
     */
    private static $defaults;

    /**
     * @return float
     */
    public function getCompression()
    {
        return $this->compression;
    }

    /**
     * @param float $compression
     * @return $this
     */
    public function setCompression($compression)
    {
        $this->compression = $compression;
        return $this;
    }

    /**
     * @return float
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param float $quality
     * @return $this
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
        return $this;
    }

    public static function defaults()
    {
        if (!isset(self::$defaults)) {
            self::$defaults = new EncodingOptions();
        }
        return self::$defaults;
    }
}
