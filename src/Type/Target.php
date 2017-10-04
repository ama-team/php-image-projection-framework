<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\Specification;

class Target
{
    /**
     * @var MappingInterface
     */
    private $mapping;
    /**
     * @var Specification
     */
    private $specification;
    /**
     * @var string[]
     */
    private $faces;
    /**
     * @var string
     */
    private $format;
    /**
     * @var EncodingOptions
     */
    private $encoding;

    /**
     * @return MappingInterface
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @param MappingInterface $mapping
     * @return Target
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getFaces()
    {
        return $this->faces;
    }

    /**
     * @param string[] $faces
     * @return Target
     */
    public function setFaces($faces)
    {
        $this->faces = $faces;
        return $this;
    }

    /**
     * @return Specification
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * @param Specification $specification
     * @return $this
     */
    public function setSpecification($specification)
    {
        $this->specification = $specification;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return Target
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return EncodingOptions
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @param EncodingOptions $encoding
     * @return Target
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        return $this;
    }
}
