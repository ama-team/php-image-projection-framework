<?php

namespace AmaTeam\Image\Projection\Framework;

use AmaTeam\Image\Projection\API\Conversion\ListenerInterface;
use AmaTeam\Image\Projection\API\Conversion\ProcessorInterface;
use AmaTeam\Image\Projection\API\Framework\ProcessingOptionsInterface;

class ProcessingOptions implements ProcessingOptionsInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $processors = [];
    /**
     * @var ListenerInterface[]
     */
    private $listeners = [];

    /**
     * @return ProcessorInterface[]
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * @param ProcessorInterface[] $processors
     * @return $this
     */
    public function setProcessors($processors)
    {
        $this->processors = $processors;
        return $this;
    }

    /**
     * @return ListenerInterface[]
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @param ListenerInterface[] $listeners
     * @return $this
     */
    public function setListeners($listeners)
    {
        $this->listeners = $listeners;
        return $this;
    }
}
