<?php

namespace AmaTeam\Image\Projection\API;

use AmaTeam\Image\Projection\API\Framework\ProcessingOptionsInterface;
use AmaTeam\Image\Projection\API\Type\SourceOptionsInterface;
use AmaTeam\Image\Projection\API\Type\TargetOptionsInterface;

interface ConversionOptionsInterface
{
    /**
     * @return SourceOptionsInterface
     */
    public function getSource();

    /**
     * @return TargetOptionsInterface
     */
    public function getTarget();

    /**
     * @return ProcessingOptionsInterface
     */
    public function getProcessing();
}
