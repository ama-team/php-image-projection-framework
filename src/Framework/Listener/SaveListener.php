<?php

namespace AmaTeam\Image\Projection\Framework\Listener;

use AmaTeam\Image\Projection\Framework\ListenerInterface;
use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\Image\Format;
use AmaTeam\Image\Projection\Specification;
use AmaTeam\Image\Projection\Tile\Tile;

class SaveListener implements ListenerInterface
{
    /**
     * @var string
     */
    private $format;
    /**
     * @var EncodingOptions
     */
    private $encoding;

    /**
     * @param string $format
     * @param EncodingOptions $encoding
     */
    public function __construct(
        $format = Format::JPEG,
        EncodingOptions $encoding = null
    ) {
        $this->format = $format;
        $this->encoding = $encoding ?: EncodingOptions::defaults();
    }

    public function accept(Tile $tile, Specification $specification)
    {
        $tile->persist($this->format, $this->encoding);
    }
}
