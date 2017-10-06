<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\Geometry\Box;

class SizeExtractor
{
    /**
     * @param TileInterface[][] $face
     * @return Box|null
     */
    public static function extractTileSize(array $face)
    {
        if (sizeof($face) === 0 || sizeof($face[0]) === 0) {
            return null;
        }
        $image = $face[0][0]->getImage();
        return new Box($image->getWidth(), $image->getHeight());
    }

    /**
     * @param TileInterface[][] $face
     * @return Box
     */
    public static function extractSize(array $face)
    {
        $layout = self::calculateLayout($face);
        $tileSize = self::extractTileSize($face);
        if (!$tileSize) {
            return null;
        }
        return new Box(
            $tileSize->getWidth() * $layout->getWidth(),
            $tileSize->getHeight() * $layout->getHeight()
        );
    }

    /**
     * @param TileInterface[][] $face
     * @return Box
     */
    public static function calculateLayout(array $face)
    {
        $rows = sizeof($face);
        $columns = $rows === 0 ? 0 : sizeof($face[0]);
        return new Box($columns, $rows);
    }
}
