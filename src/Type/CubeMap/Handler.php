<?php

namespace AmaTeam\Image\Projection\Type\CubeMap;

use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\Type\AbstractHandler;

class Handler extends AbstractHandler
{
    const TYPE = 'CubeMap';

    /**
     * @inheritDoc
     */
    protected function createMapping(Box $size)
    {
        return new Mapping($size->getHeight());
    }
}
