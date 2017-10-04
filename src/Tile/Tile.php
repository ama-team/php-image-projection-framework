<?php

namespace AmaTeam\Image\Projection\Tile;

use AmaTeam\Image\Projection\Image\Adapter\ImageInterface;
use AmaTeam\Image\Projection\Image\EncodingOptions;
use AmaTeam\Image\Projection\Image\Manager;

/**
 * Represents single tile - rectangular projection chunk.
 */
class Tile
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var Manager
     */
    private $manager;
    /**
     * @var Position
     */
    private $position;
    /**
     * @var ImageInterface
     */
    private $image;

    /**
     * @param string $path
     * @param Position $position
     * @param Manager $manager
     */
    public function __construct(
        $path,
        Position $position,
        Manager $manager
    ) {
        $this->path = $path;
        $this->manager = $manager;
        $this->position = $position;
    }

    public function getImage()
    {
        if (!$this->image) {
            $this->image = $this->manager->read($this->path);
        }
        return $this->image;
    }

    public function createImage($width, $height)
    {
        $this->image = $this->manager->create($width, $height);
        return $this->image;
    }

    public function release()
    {
        $this->image = null;
        return $this;
    }

    public function persist($format, EncodingOptions $options = null)
    {
        if ($this->image) {
            $this->manager->save($this->image, $this->path, $format, $options);
        }
        return $this;
    }

    /**
     * @return Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Converts single level tiles array into three level tree
     * (face -> rows -> columns).
     *
     * @param Tile[] $tiles
     * @return Tile[][][]
     */
    public static function treeify(array $tiles)
    {
        $target = [];
        foreach ($tiles as $tile) {
            $position = $tile->getPosition();
            $cursor = &$target;
            foreach ([$position->getFace(), $position->getY()] as $segment) {
                if (!isset($cursor[$segment])) {
                    $cursor[$segment] = [];
                }
                $cursor = &$cursor[$segment];
            }
            $cursor[$position->getX()] = $tile;
        }
        return $target;
    }

    /**
     * @param Tile[][][] $tree
     * @return Tile[]
     */
    public static function flatten(array $tree)
    {
        $target = [];
        foreach ($tree as $face) {
            foreach ($face as $row) {
                foreach ($row as $tile) {
                    $target[] = $tile;
                }
            }
        }
        return $target;
    }
}
