<?php

namespace AmaTeam\Image\Projection\Filesystem;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

class Factory
{
    /**
     * @return FilesystemInterface
     */
    public static function create()
    {
        $adapter = new Local(getcwd());
        return new Filesystem($adapter);
    }
}
