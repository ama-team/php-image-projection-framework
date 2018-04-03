<?php

namespace AmaTeam\Image\Projection\Image\Adapter;

use AmaTeam\Image\Projection\API\Image\ImageFactoryInterface;
use RuntimeException;

class Discovery
{
    // storing as static variable because older HHVM doesn't allow to
    // store arrays in constants

    private static $factories = [
        Gd\ImageFactory::class,
        Imagick\ImageFactory::class,
    ];

    /**
     * @return ImageFactoryInterface
     */
    public static function find()
    {
        foreach (self::$factories as $backend) {
            if ($backend::supported()) {
                return new $backend;
            }
        }
        $message = 'Couldn\'t find any image processing backend, please ' .
            'install gd (preferred) / imagick.';
        throw new RuntimeException($message);
    }
}
