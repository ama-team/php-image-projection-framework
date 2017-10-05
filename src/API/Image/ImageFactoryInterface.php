<?php

namespace AmaTeam\Image\Projection\API\Image;

interface ImageFactoryInterface
{
    /**
     * Reads file from binary data
     *
     * @param string $blob
     * @return ImageInterface
     */
    public function read($blob);

    /**
     * @param int $width
     * @param int $height
     * @return ImageInterface
     */
    public function create($width, $height);
}
