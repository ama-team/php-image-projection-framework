<?php

namespace AmaTeam\Image\Projection\Type\Equirectangular;

use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\Type\AbstractHandler;

class Handler extends AbstractHandler
{
    const TYPE = 'Equirectangular';

    /**
     * @inheritDoc
     */
    public function createMapping(Box $size)
    {
        return new Mapping($size->getWidth(), $size->getHeight());
    }
}
