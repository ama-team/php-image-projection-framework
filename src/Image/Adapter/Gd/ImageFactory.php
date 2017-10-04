<?php

namespace AmaTeam\Image\Projection\Image\Adapter\Gd;

use AmaTeam\Image\Projection\Image\Adapter\ImageFactoryInterface;

class ImageFactory implements ImageFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function read($blob)
    {
        $resource = imagecreatefromstring($blob);
        return new Image($resource);
    }

    /**
     * @inheritDoc
     */
    public function create($width, $height)
    {
        $resource = imagecreatetruecolor($width, $height);
        return new Image($resource);
    }

    public static function supported()
    {
        return function_exists('imagecreatetruecolor');
    }
}
