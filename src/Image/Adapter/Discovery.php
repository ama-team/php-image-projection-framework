<?php

namespace AmaTeam\Image\Projection\Image\Adapter;

use AmaTeam\Image\Projection\API\Image\ImageFactoryInterface;
use RuntimeException;

class Discovery
{
    const FACTORIES = [
        Gd\ImageFactory::class,
        Imagick\ImageFactory::class,
    ];

    /**
     * @return ImageFactoryInterface
     */
    public static function find()
    {
        foreach (self::FACTORIES as $backend) {
            if ($backend::supported()) {
                return new $backend;
            }
        }
        $message = 'Couldn\'t find any image processing backend, please ' .
            'install gd (preferred) / imagick.';
        throw new RuntimeException($message);
    }
}
