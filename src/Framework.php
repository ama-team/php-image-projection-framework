<?php

namespace AmaTeam\Image\Projection;

use AmaTeam\Image\Projection\Framework\ConversionPipeline;
use AmaTeam\Image\Projection\Framework\FilterInterface;
use AmaTeam\Image\Projection\Framework\Listener\SaveListener;
use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\Image\Format;
use AmaTeam\Image\Projection\Type\HandlerInterface;
use AmaTeam\Image\Projection\Type\ReaderInterface;
use AmaTeam\Image\Projection\Type\Registry;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Framework
{
    /**
     * @var Registry
     */
    private $registry;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Registry $registry
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        Registry $registry = null,
        LoggerInterface $logger = null
    ) {
        $logger = $logger ?: new NullLogger();
        if (!$registry) {
            $registry = (new Registry(null, null, $logger))
                ->registerDefaultTypes();
        }
        $this->registry = $registry;
        $this->logger = $logger;
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
     * @param Specification $source
     * @param Specification $target
     * @param string $format
     * @param EncodingOptions $options
     */
    public function convert(
        Specification $source,
        Specification $target,
        $format = Format::JPEG,
        EncodingOptions $options = null
    ) {
        $persistenceListener = new SaveListener($format, $options);
        $pipeline = $this->createConversion($source, $target);
        $pipeline->addListener($persistenceListener);
        $pipeline->run();
    }

    /**
     * @param Specification $source
     * @param Specification[] $targets
     * @param string $format
     * @param EncodingOptions|null $options
     */
    public function convertAll(
        Specification $source,
        array $targets,
        $format = Format::JPEG,
        EncodingOptions $options = null
    ) {
        $pipelines = $this->createConversions($source, $targets);
        $listener = new SaveListener($format, $options);
        foreach ($pipelines as $pipeline) {
            $pipeline->addListener($listener);
            $pipeline->run();
        }
    }

    /**
     * @param Specification $source
     * @param Specification[] $targets
     * @param FilterInterface[] $filters
     * @return ConversionPipeline[]
     */
    public function createConversions(
        Specification $source,
        array $targets,
        ...$filters
    ) {
        $reader = $this->getHandler($source->getType())->read($source);
        $pipelines = [];
        foreach ($targets as $target) {
            $pipelines[] = $this->createPipeline($reader, $target, $filters);
        }
        return $pipelines;
    }

    /**
     * Creates conversion pipeline for single target.
     *
     * @param Specification $source
     * @param Specification $target
     * @param FilterInterface[] $filters
     * @return ConversionPipeline
     */
    public function createConversion(
        Specification $source,
        Specification $target,
        ...$filters
    ) {
        $reader = $this->getHandler($source->getType())->read($source);
        return $this->createPipeline($reader, $target, $filters);
    }

    /**
     * @param ReaderInterface $source
     * @param Specification $target
     * @param FilterInterface[] $filters
     * @return ConversionPipeline
     */
    private function createPipeline(
        ReaderInterface $source,
        Specification $target,
        array $filters
    ) {
        $generator = $this
            ->getHandler($target->getType())
            ->createGenerator($source, $target, $filters);
        return new ConversionPipeline($target, $generator);
    }

    /**
     * @return Registry
     */
    public function getRegistry()
    {
        return $this->registry;
    }
}
