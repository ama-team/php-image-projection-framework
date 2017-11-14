<?php

namespace AmaTeam\Image\Projection\API;

use AmaTeam\Image\Projection\API\Type\HandlerInterface;

interface RegistryInterface
{
    /**
     * @param string $type
     * @param HandlerInterface $handler
     * @return void
     */
    public function register($type, HandlerInterface $handler);

    /**
     * @return string[]
     */
    public function getRegisteredTypes();

    /**
     * @param string $type
     * @return HandlerInterface
     */
    public function getHandler($type);

    /**
     * Same as getHandler, but returns null instead of throwing
     * exception.
     *
     * @param string $type
     * @return HandlerInterface|null
     */
    public function findHandler($type);

    /**
     * Returns correct type name by performing prefix-based search
     *
     * @param string $type
     * @return string|null
     */
    public function findType($type);
}
