<?php

namespace AmaTeam\Image\Projection\API\Framework;

use AmaTeam\Image\Projection\API\Conversion\ListenerInterface;
use AmaTeam\Image\Projection\API\Conversion\ProcessorInterface;

interface ProcessingOptionsInterface
{
    /**
     * @return ProcessorInterface[]
     */
    public function getProcessors();

    /**
     * @return ListenerInterface[]
     */
    public function getListeners();
}
