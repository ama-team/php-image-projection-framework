<?php

namespace AmaTeam\Image\Projection;

use AmaTeam\Image\Projection\API\ConversionInterface;
use AmaTeam\Image\Projection\API\ConverterInterface;
use AmaTeam\Image\Projection\API\FrameworkInterface;
use AmaTeam\Image\Projection\API\RegistryInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\Framework\Converter;
use AmaTeam\Image\Projection\Conversion\Listener\SaveListener;
use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\API\Image\Format;
use AmaTeam\Image\Projection\API\Type\HandlerInterface;
use AmaTeam\Image\Projection\Type\Registry;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Framework implements FrameworkInterface
{
    /**
     * @var RegistryInterface
     */
    private $registry;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * @param RegistryInterface $registry
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        RegistryInterface $registry = null,
        LoggerInterface $logger = null
    ) {
        $logger = $logger ?: new NullLogger();
        if (!$registry) {
            $registry = (new Registry(null, null, $logger))
                ->registerDefaultTypes();
        }
        $this->registry = $registry;
        $this->logger = $logger;
        $this->converter = new Converter($registry, $logger);
    }

    /**
     * @param string $type
     *
     * @return HandlerInterface
     */
    public function getHandler($type)
    {
        return $this->registry->getHandler($type);
    }

    /**
     * @param SpecificationInterface $source
     * @param SpecificationInterface $target
     * @param string $format
     * @param EncodingOptions $options
     */
    public function convert(
        SpecificationInterface $source,
        SpecificationInterface $target,
        $format = Format::JPEG,
        EncodingOptions $options = null
    ) {
        $conversion = $this->converter->createConversion($source, $target);
        $this->processConversion($conversion, $format, $options);
    }

    /**
     * @param SpecificationInterface $source
     * @param SpecificationInterface[] $targets
     * @param string $format
     * @param EncodingOptions|null $options
     */
    public function convertAll(
        SpecificationInterface $source,
        array $targets,
        $format = Format::JPEG,
        EncodingOptions $options = null
    ) {
        $conversions = $this->converter->createConversions($source, $targets);
        foreach ($conversions as $conversion) {
            $this->processConversion($conversion, $format, $options);
        }
    }

    /**
     * @param ConversionInterface $conversion
     * @param string $format
     * @param EncodingOptions $options
     * @return void
     */
    private function processConversion(
        ConversionInterface $conversion,
        $format,
        EncodingOptions $options = null
    ) {
        $filesystem = $this->registry->getFilesystem();
        $listener = new SaveListener(
            $filesystem,
            $format,
            $options,
            $this->logger
        );
        $conversion->addListener($listener);
        $conversion->run();
    }

    /**
     * Registers new type handler
     *
     * @param string $type
     * @param HandlerInterface $handler
     * @return $this
     */
    public function register($type, HandlerInterface $handler)
    {
        $this->registry->register($type, $handler);
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRegisteredTypes()
    {
        return $this->registry->getRegisteredTypes();
    }

    /**
     * @return ConverterInterface
     */
    public function getConverter()
    {
        return $this->converter;
    }

    /**
     * @return RegistryInterface
     */
    public function getRegistry()
    {
        return $this->registry;
    }
}
