<?php

namespace AmaTeam\Image\Projection\API\Type;

use AmaTeam\Image\Projection\API\Conversion\FilterInterface;

interface TargetOptionsInterface
{
    /**
     * @return FilterInterface[]
     */
    public function getFilters();

    /**
     * @return string
     */
    public function getFormat();

    /**
     * Tile generation quality, 0.0...1.0 (JPEG only)
     *
     * @return float
     */
    public function getQuality();

    /**
     * Tile compression level, 0.0...1.0 (PNG only)
     *
     * @return float
     */
    public function getCompression();
}
