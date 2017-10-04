<?php

namespace AmaTeam\Image\Projection\Test\Support;

class Structure
{
    public static function getProjectRoot()
    {
        $directory = __DIR__;
        for ($i = 0; $i < 2; $i++) {
            $directory = dirname($directory);
        }
        return $directory;
    }
}
