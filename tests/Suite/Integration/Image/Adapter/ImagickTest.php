<?php

namespace AmaTeam\Image\Projection\Test\Suite\Integration\Image\Adapter;

use AmaTeam\Image\Projection\Image\Adapter\Imagick\ImageFactory;
use Codeception\Test\Unit;

class ImagickTest extends Unit
{
    public function testImagickAdapter()
    {
        TestSuite::run(new ImageFactory());
    }
}
