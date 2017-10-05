<?php

namespace AmaTeam\Image\Projection\Framework;

use AmaTeam\Image\Projection\API\ConverterInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;
use AmaTeam\Image\Projection\API\Conversion\FilterInterface;
use AmaTeam\Image\Projection\Type\Registry;

class Converter implements ConverterInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param SpecificationInterface $source
     * @param SpecificationInterface[] $targets
     * @param FilterInterface[] $filters
     * @return Conversion[]
     */
    public function createConversions(
        SpecificationInterface $source,
        array $targets,
        ...$filters
    ) {
        $reader = $this->getReader($source);
        $pipelines = [];
        foreach ($targets as $target) {
            $pipelines[] = $this
                ->instantiateConversion($reader, $target, $filters);
        }
        return $pipelines;
    }

    /**
     * Creates conversion pipeline for single target.
     *
     * @param SpecificationInterface $source
     * @param SpecificationInterface $target
     * @param FilterInterface[] $filters
     * @return Conversion
     */
    public function createConversion(
        SpecificationInterface $source,
        SpecificationInterface $target,
        ...$filters
    ) {
        $reader = $this->getReader($source);
        return $this->instantiateConversion($reader, $target, $filters);
    }

    /**
     * @param SpecificationInterface $source
     * @return ReaderInterface
     */
    private function getReader(SpecificationInterface $source)
    {
        return $this
            ->registry
            ->getHandler($source->getType())
            ->read($source);
    }

    /**
     * @param ReaderInterface $source
     * @param SpecificationInterface $target
     * @param FilterInterface[] $filters
     * @return Conversion
     */
    private function instantiateConversion(
        ReaderInterface $source,
        SpecificationInterface $target,
        array $filters
    ) {
        $generator = $this
            ->registry
            ->getHandler($target->getType())
            ->createGenerator($source, $target, $filters);
        return new Conversion($target, $generator);
    }
}
