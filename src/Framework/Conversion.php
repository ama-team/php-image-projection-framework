<?php

namespace AmaTeam\Image\Projection\Framework;

use AmaTeam\Image\Projection\API\ConversionInterface;
use AmaTeam\Image\Projection\API\Conversion\ListenerInterface;
use AmaTeam\Image\Projection\API\Conversion\ProcessorInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\API\Type\GeneratorInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Single-run conversion processing that encapsulates all userspace handlers.
 */
class Conversion implements ConversionInterface
{
    /**
     * @var SpecificationInterface
     */
    private $target;
    /**
     * @var GeneratorInterface
     */
    private $generator;
    /**
     * @var ListenerInterface[]
     */
    private $listeners = [];
    /**
     * @var ProcessorInterface[][]
     */
    private $processors = [];
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param SpecificationInterface $target
     * @param GeneratorInterface $generator
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        SpecificationInterface $target,
        GeneratorInterface $generator,
        LoggerInterface $logger = null
    ) {
        $this->target = $target;
        $this->generator = $generator;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * Runs whole pipeline. Tiles are destroyed after last listener has been
     * called.
     */
    public function run()
    {
        foreach ($this->generator as $tile) {
            $context = ['tile' => $tile->getPosition()];
            $this->logger->debug('Generated tile {tile}', $context);
            $tile = $this->applyProcessors($tile, $this->target);
            $this->notifyListeners($tile, $this->target);
        }
    }

    /**
     * @param TileInterface $tile
     * @param SpecificationInterface $specification
     * @return TileInterface
     */
    private function applyProcessors(
        TileInterface $tile,
        SpecificationInterface $specification
    ) {
        foreach ($this->processors as $processors) {
            foreach ($processors as $processor) {
                $context = [
                    'tile' => $tile->getPosition(),
                    'processor' => $processor
                ];
                $template = 'Applying processor {processor} to tile {tile}';
                $this->logger->debug($template, $context);
                $candidate = $processor->process($tile, $specification);
                $tile = $candidate instanceof TileInterface ? $candidate : $tile;
            }
        }
        return $tile;
    }

    /**
     * @param TileInterface $tile
     * @param SpecificationInterface $specification
     */
    private function notifyListeners(
        TileInterface $tile,
        SpecificationInterface $specification
    ) {
        foreach ($this->listeners as $listener) {
            $context = [
                'tile' => $tile->getPosition(),
                'listener' => $listener
            ];
            $template = 'Applying listener {listener} to tile {tile}';
            $this->logger->debug($template, $context);
            $listener->accept($tile, $specification);
        }
    }

    /**
     * @param ProcessorInterface $processor
     * @param int $order Order in which processors are applied (lower order.
     * values force processors to be run earlier). Processor with same order
     * value will be run in the order they were added.
     * priority
     *
     * @return $this
     */
    public function addProcessor(ProcessorInterface $processor, $order = 0)
    {
        if ($processor instanceof LoggerAwareInterface) {
            $processor->setLogger($this->logger);
        }
        if (!isset($this->processors[$order])) {
            $this->processors[$order] = [];
        }
        $this->processors[$order][] = $processor;
        ksort($this->processors);
        return $this;
    }

    /**
     * @param ListenerInterface $listener
     * @return $this
     */
    public function addListener(ListenerInterface $listener)
    {
        if ($listener instanceof LoggerAwareInterface) {
            $listener->setLogger($this->logger);
        }
        $this->listeners[] = $listener;
        return $this;
    }
}
