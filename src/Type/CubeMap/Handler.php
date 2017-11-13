<?php

namespace AmaTeam\Image\Projection\Type\CubeMap;

use AmaTeam\Image\Projection\Constants;
use AmaTeam\Image\Projection\Type\AbstractHandler;

class Handler extends AbstractHandler
{
    const TYPE = Constants::TYPE_CUBE_MAP;

    /**
     * @inheritDoc
     */
    protected function getMapping()
    {
        return new Mapping();
    }
}
