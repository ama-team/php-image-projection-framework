<?php

namespace AmaTeam\Image\Projection\Framework;

use AmaTeam\Image\Projection\Specification;
use AmaTeam\Image\Projection\Tile\Tile;
use AmaTeam\Image\Projection\Type\GeneratorInterface;

/**
 * Single-run conversion processing that encapsulates all userspace handlers.
 */
class ConversionPipeline
{
    /**
     * @var Specification
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
     * @param Specification $target
     * @param GeneratorInterface $generator
     */
    public function __construct(
        Specification $target,
        GeneratorInterface $generator
    ) {
        $this->target = $target;
        $this->generator = $generator;
    }

    /**
     * Runs whole pipeline. Tiles are destroyed after last listener has been
     * called.
     */
    public function run()
    {
        foreach ($this->generator as $tile) {
            $this->applyProcessors($tile, $this->target);
            $this->notifyListeners($tile, $this->target);
        }
    }

    /**
     * @param Tile $tile
     * @param Specification $specification
     */
    private function applyProcessors(Tile $tile, Specification $specification)
    {
        foreach ($this->processors as $processors) {
            foreach ($processors as $processor) {
                $processor->process($tile, $specification);
            }
        }
    }

    /**
     * @param Tile $tile
     * @param Specification $specification
     */
    private function notifyListeners(Tile $tile, Specification $specification)
    {
        foreach ($this->listeners as $listener) {
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
        if (!$this->processors[$order]) {
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
        $this->listeners[] = $listener;
        return $this;
    }
}
