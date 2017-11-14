<?php

namespace AmaTeam\Image\Projection\Framework;

use AmaTeam\Image\Projection\API\ConversionOptionsInterface;
use AmaTeam\Image\Projection\API\Framework\ProcessingOptionsInterface;
use AmaTeam\Image\Projection\API\Type\SourceOptionsInterface;
use AmaTeam\Image\Projection\API\Type\TargetOptionsInterface;

class ConversionOptions implements ConversionOptionsInterface
{
    /**
     * @var SourceOptionsInterface
     */
    private $source;
    /**
     * @var TargetOptionsInterface
     */
    private $target;
    /**
     * @var ProcessingOptionsInterface
     */
    private $processing;

    /**
     * @return SourceOptionsInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param SourceOptionsInterface $source
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return TargetOptionsInterface
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param TargetOptionsInterface $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return ProcessingOptionsInterface
     */
    public function getProcessing()
    {
        return $this->processing;
    }

    /**
     * @param ProcessingOptionsInterface $processing
     * @return $this
     */
    public function setProcessing($processing)
    {
        $this->processing = $processing;
        return $this;
    }
}
