<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\Conversion\FilterInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;

class GenerationDetails
{
    /**
     * @var ReaderInterface
     */
    private $source;
    /**
     * @var MappingInterface
     */
    private $mapping;
    /**
     * @var SpecificationInterface
     */
    private $specification;
    /**
     * @var FilterInterface[]
     */
    private $filters;

    /**
     * @param ReaderInterface $source
     * @param MappingInterface $mapping
     * @param SpecificationInterface $specification
     * @param FilterInterface[] $filters
     */
    public function __construct(
        ReaderInterface $source,
        MappingInterface $mapping,
        SpecificationInterface $specification,
        array $filters = []
    ) {
        $this->source = $source;
        $this->mapping = $mapping;
        $this->specification = $specification;
        $this->filters = $filters;
    }

    /**
     * @return ReaderInterface
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return MappingInterface
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @return SpecificationInterface
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }
}
