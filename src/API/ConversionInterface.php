<?php

namespace AmaTeam\Image\Projection\API;

use AmaTeam\Image\Projection\API\Conversion\ListenerInterface;
use AmaTeam\Image\Projection\API\Conversion\ProcessorInterface;

interface ConversionInterface
{
    /**
     * @return void
     */
    public function run();

    /**
     * @param ProcessorInterface $processor
     * @param int $order
     * @return $this
     */
    public function addProcessor(ProcessorInterface $processor, $order = 0);

    /**
     * @param ListenerInterface $listener
     * @return $this
     */
    public function addListener(ListenerInterface $listener);
}
