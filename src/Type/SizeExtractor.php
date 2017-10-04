<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\Geometry\Box;
use AmaTeam\Image\Projection\Tile\Tile;

class SizeExtractor
{
    /**
     * @param Tile[][] $tiles
     * @return Box|null
     */
    public static function extractTileSize(array $tiles)
    {
        if (sizeof($tiles) === 0 || sizeof($tiles[0]) === 0) {
            return null;
        }
        $image = $tiles[0][0]->getImage();
        return new Box($image->getWidth(), $image->getHeight());
    }

    /**
     * @param Tile[][] $tiles
     * @return Box
     */
    public static function extractSize(array $tiles)
    {
        $layout = self::calculateLayout($tiles);
        $tileSize = self::extractTileSize($tiles);
        if (!$tileSize) {
            return null;
        }
        return new Box(
            $tileSize->getWidth() * $layout->getWidth(),
            $tileSize->getHeight() * $layout->getHeight()
        );
    }

    /**
     * @param Tile[][] $tiles
     * @return Box
     */
    public static function calculateLayout(array $tiles)
    {
        $rows = sizeof($tiles);
        $columns = $rows === 0 ? 0 : sizeof($tiles[0]);
        return new Box($columns, $rows);
    }
}
