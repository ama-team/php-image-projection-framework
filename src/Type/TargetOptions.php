<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\Conversion\FilterInterface;
use AmaTeam\Image\Projection\API\Image\Format;
use AmaTeam\Image\Projection\API\Type\TargetOptionsInterface;

class TargetOptions implements TargetOptionsInterface
{
    const DEFAULT_QUALITY = 0.9;
    const DEFAULT_COMPRESSION = 1.0;
    const DEFAULT_FORMAT = Format::JPEG;

    /**
     * @var FilterInterface[]
     */
    private $filters = [];
    /**
     * @var string
     */
    private $format = self::DEFAULT_FORMAT;
    /**
     * @var float
     */
    private $quality = self::DEFAULT_QUALITY;
    /**
     * @var float
     */
    private $compression = self::DEFAULT_COMPRESSION;

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param FilterInterface[] $filters
     * @return $this
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
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
}
