<?php

namespace AmaTeam\Image\Projection\Conversion\Listener;

use AmaTeam\Image\Projection\API\Conversion\ListenerInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\API\Image\Format;
use AmaTeam\Image\Projection\Tile\Tile;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param string $format
     * @param EncodingOptions $encoding
     * @param LoggerInterface $logger
     */
    public function __construct(
        $format = Format::JPEG,
        EncodingOptions $encoding = null,
        LoggerInterface $logger = null
    ) {
        $this->format = $format;
        $this->encoding = $encoding ?: EncodingOptions::defaults();
        $this->logger = $logger ?: new NullLogger();
    }

    public function accept(Tile $tile, SpecificationInterface $specification)
    {
        $parameters = ['position' => $tile->getPosition()];
        $this->logger->debug('Saving tile {position}', $parameters);
        $tile->persist($this->format, $this->encoding);
    }
}
