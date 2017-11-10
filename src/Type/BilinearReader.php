<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\API\Type\MappingInterface;
use AmaTeam\Image\Projection\API\Type\ReaderInterface;

class BilinearReader implements ReaderInterface
{
    /**
     * @var MappingInterface
     */
    private $mapping;
    /**
     * @var TileInterface[][][]
     */
    private $tiles;
    /**
     * @var int
     */
    private $height;
    /**
     * @var int
     */
    private $width;
    /**
     * @var int
     */
    private $tileWidth;
    /**
     * @var int
     */
    private $tileHeight;

    /**
     * @param SpecificationInterface $specification
     * @param MappingInterface $mapping
     * @param TileInterface[][][] $tiles
     */
    public function __construct(
        SpecificationInterface $specification,
        MappingInterface $mapping,
        array $tiles
    ) {
        $this->mapping = $mapping;
        $this->tiles = $tiles;
        $this->width = $specification->getSize()->getWidth();
        $this->height = $specification->getSize()->getHeight();
        $this->tileWidth = $specification->getTileSize()->getWidth();
        $this->tileHeight = $specification->getTileSize()->getHeight();
    }

    /**
     * @inheritDoc
     */
    public function getColorAt($latitude, $longitude)
    {
        $position = $this
            ->mapping
            ->getPosition($latitude, $longitude);
        return self::mix($this->getColorsAt($position[0], $position[1], $position[2]));
    }

    /**
     * This method samples texel this specific point belongs to, as
     * well as three closest to it. After all texels have been sampled,
     * each is mixed in according to it's weight (proximity to UV
     * point).
     *
     * @param string|int $face
     * @param float $u
     * @param float $v
     * @return array
     */
    private function getColorsAt($face, $u, $v)
    {
        $colors = [];
        $normalizedU = $u * $this->width - 0.5;
        $normalizedV = $v * $this->height - 0.5;
        $x1 = (int) $normalizedU;
        $y1 = (int) $normalizedV;
        $x2 = $x1 + 1;
        $y2 = $y1 + 1;
        foreach ([$x1, $x2] as $x) {
            foreach ([$y1, $y2] as $y) {
                if ($x < 0 || $x >= $this->width || $y < 0 || $y >= $this->height) {
                    continue;
                }
                $column = (int) ($x / $this->tileWidth);
                $row = (int) ($y / $this->tileHeight);
                $tileX = $x % $this->tileWidth;
                $tileY = $y % $this->tileHeight;
                $color = $this
                    ->tiles[$face][$row][$column]
                    ->getImage()
                    ->getColorAt($tileX, $tileY);
                $diffX = $x - $normalizedU;
                $diffY = $y - $normalizedV;
                $weight = 1 / sqrt($diffY * $diffY + $diffX * $diffX);
                $colors[] = [$color, $weight];
            }
        }
        return $colors;
    }

    private static function mix(array $colors)
    {
        $red = 0;
        $green = 0;
        $blue = 0;
        $alpha = 0;
        $weight = 0;
        foreach ($colors as $source) {
            $sourceRed = ($source[0] >> 24) & 0xFF;
            $sourceGreen = ($source[0] >> 16) & 0xFF;
            $sourceBlue = ($source[0] >> 8) & 0xFF;
            $sourceAlpha = $source[0] & 0xFF;
            $weight += $source[1];
            $red += $sourceRed * $source[1];
            $green += $sourceGreen * $source[1];
            $blue += $sourceBlue * $source[1];
            $alpha += $sourceAlpha * $source[1];
        }
        $red /= $weight;
        $blue /= $weight;
        $green /= $weight;
        $alpha /= $weight;
        return ((int) $red) << 24 | ((int) $green) << 16 | ((int) $blue) << 8 | ((int) $alpha);
    }
}
