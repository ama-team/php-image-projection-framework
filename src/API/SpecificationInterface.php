<?php

namespace AmaTeam\Image\Projection\API;

use AmaTeam\Image\Projection\Filesystem\Pattern;
use AmaTeam\Image\Projection\Geometry\Box;

interface SpecificationInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return Box|null
     */
    public function getLayout();

    /**
     * @return Box|null
     */
    public function getTileSize();

    /**
     * @return Box|null
     */
    public function getSize();

    /**
     * @return Pattern|null
     */
    public function getPattern();
}
