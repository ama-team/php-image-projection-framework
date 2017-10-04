<?php

namespace AmaTeam\Image\Projection\Test\Suite\Integration\Adapter;

use AmaTeam\Image\Projection\Image\Adapter\Gd\ImageFactory;
use Codeception\Test\Unit;

class GdTest extends Unit
{
    public function testGdAdapter()
    {
        TestSuite::run(new ImageFactory());
    }
}
