<?php

namespace AmaTeam\Image\Projection\Type\Equirectangular;

use AmaTeam\Image\Projection\Constants;
use AmaTeam\Image\Projection\Type\AbstractHandler;

class Handler extends AbstractHandler
{
    const TYPE = Constants::TYPE_EQUIRECTANGULAR;

    /**
     * @inheritDoc
     */
    public function getMapping()
    {
        return new Mapping();
    }
}
