<?php

namespace AmaTeam\Image\Projection\Framework;

use AmaTeam\Image\Projection\Specification;
use AmaTeam\Image\Projection\Tile\Tile;

/**
 * Listeners allows to receive tile and create some side effect based on it -
 * create log message, notify build system, persist tile to disk.
 */
interface ListenerInterface
{
    public function accept(Tile $tile, Specification $specification);
}
