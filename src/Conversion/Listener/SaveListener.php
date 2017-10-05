<?php

namespace AmaTeam\Image\Projection\Conversion\Listener;

use AmaTeam\Image\Projection\API\Conversion\ListenerInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\API\Image\Format;
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

    public function accept(Tile $tile, SpecificationInterface $specification)
    {
        $tile->persist($this->format, $this->encoding);
    }
}
