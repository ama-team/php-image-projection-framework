<?php

namespace AmaTeam\Image\Projection\Image\Adapter\Imagick;

use AmaTeam\Image\Projection\Image\Adapter\ImageFactoryInterface;
use Imagick;

class ImageFactory implements ImageFactoryInterface
{
    public function read($blob)
    {
        $resource = new Imagick();
        $resource->readImageBlob($blob);
        return new Image($resource);
    }

    public function create($width, $height)
    {
        $resource = new Imagick();
        $resource->newImage($width, $height, 'none');
        return new Image($resource);
    }

    public function supported()
    {
        return class_exists('\Imagick');
    }
}
