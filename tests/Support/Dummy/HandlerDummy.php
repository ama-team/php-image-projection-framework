<?php

namespace AmaTeam\Image\Projection\Test\Support\Dummy;

use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\Image\Manager;
use AmaTeam\Image\Projection\Type\AbstractHandler;
use AmaTeam\Image\Projection\Type\MappingInterface;
use League\Flysystem\FilesystemInterface;

class HandlerDummy extends AbstractHandler
{
    private $mapping;
    /**
     * @inheritDoc
     */
    public function __construct(
        FilesystemInterface $filesystem,
        Manager $imageManager,
        MappingInterface $mapping
    ) {
        $this->mapping = $mapping;
        parent::__construct($filesystem, $imageManager);
    }

    public function createMapping(Box $size)
    {
        return $this->mapping;
    }
}
