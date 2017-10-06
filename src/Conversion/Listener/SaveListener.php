<?php

namespace AmaTeam\Image\Projection\Conversion\Listener;

use AmaTeam\Image\Projection\API\Conversion\ListenerInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\API\Image\Format;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class SaveListener implements ListenerInterface
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;
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
     * @param FilesystemInterface $filesystem
     * @param string $format
     * @param EncodingOptions $encoding
     * @param LoggerInterface $logger
     */
    public function __construct(
        FilesystemInterface $filesystem,
        $format = Format::JPEG,
        EncodingOptions $encoding = null,
        LoggerInterface $logger = null
    ) {
        $this->filesystem = $filesystem;
        $this->format = $format;
        $this->encoding = $encoding ?: EncodingOptions::defaults();
        $this->logger = $logger ?: new NullLogger();
    }

    public function accept(
        TileInterface $tile,
        SpecificationInterface $specification
    ) {
        $context = ['position' => $tile->getPosition()];
        $this->logger->debug('Saving tile {position}', $context);
        $parameters = $tile->getPosition()->toPatternParameters();
        $path = (string) $specification->getPattern()->resolve($parameters);
        $this->filesystem->put(
            (string) $path,
            $tile->getImage()->getBinary($this->format, $this->encoding)
        );
    }
}
