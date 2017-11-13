<?php

namespace AmaTeam\Image\Projection\Conversion\Processor;

use AmaTeam\Image\Projection\API\Conversion\ProcessorInterface;
use AmaTeam\Image\Projection\API\Image\ImageFactoryInterface;
use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Tile\TileInterface;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\ColorGrid;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\EdgeLumaCalculator;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\EdgeOrientationCalculator;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\EdgeDistanceCalculator;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\Edge;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\EdgeDirectionCalculator;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\LumaGrid;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\SubPixelOffsetCalculator;
use AmaTeam\Image\Projection\Conversion\Processor\FXAA\TargetColorCalculator;
use AmaTeam\Image\Projection\Image\Utility\Color;
use AmaTeam\Image\Projection\Tile\Tile;

/**
 * @see https://developer.download.nvidia.com/assets/gamedev/files/sdk/11/FXAA_WhitePaper.pdf
 * @see http://blog.simonrodriguez.fr/articles/30-07-2016_implementing_fxaa.html
 */
class FXAAProcessor implements ProcessorInterface
{
    const DEBUG_OFF = 0;
    const DEBUG_EDGE = 1;
    // Horizontal edges are
    const DEBUG_ORIENTATION = 2;
    // Edge is painted in blue, it's contrast opposite - in green
    const DEBUG_PAIR = 3;

    const EDGE_THRESHOLD_TOO_LITTLE = 1 / 3;
    const EDGE_THRESHOLD_LOW_QUALITY = 1 / 4;
    const EDGE_THRESHOLD_HIGH_QUALITY = 1 / 8;
    const EDGE_THRESHOLD_OVERKILL = 1 / 16;

    const MINIMUM_EDGE_THRESHOLD_VISIBLE_LIMIT = 1 / 32;
    const MINIMUM_EDGE_THRESHOLD_HIGH_QUALITY = 1 / 16;
    const MINIMUM_EDGE_THRESHOLD_UPPER_LIMIT = 1 / 12;

    private $edgeThreshold = self::EDGE_THRESHOLD_LOW_QUALITY;
    private $minimumEdgeThreshold = self::MINIMUM_EDGE_THRESHOLD_HIGH_QUALITY;
    private $debug = self::DEBUG_OFF;

    /**
     * @var ImageFactoryInterface
     */
    private $imageFactory;

    /**
     * @param ImageFactoryInterface $imageFactory
     */
    public function __construct(ImageFactoryInterface $imageFactory)
    {
        $this->imageFactory = $imageFactory;
    }

    /**
     * @return float|int
     */
    public function getEdgeThreshold()
    {
        return $this->edgeThreshold;
    }

    /**
     * @param float|int $edgeThreshold
     * @return $this
     */
    public function setEdgeThreshold($edgeThreshold)
    {
        $this->edgeThreshold = $edgeThreshold;
        return $this;
    }

    /**
     * @return float|int
     */
    public function getMinimumEdgeThreshold()
    {
        return $this->minimumEdgeThreshold;
    }

    /**
     * @param float|int $minimumEdgeThreshold
     * @return $this
     */
    public function setMinimumEdgeThreshold($minimumEdgeThreshold)
    {
        $this->minimumEdgeThreshold = $minimumEdgeThreshold;
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function process(TileInterface $tile, SpecificationInterface $specification)
    {
        $image = $tile->getImage();
        $width = $image->getWidth();
        $height = $image->getHeight();
        $colorGrid = new ColorGrid();
        $lumaGrid = new LumaGrid();
        $target = $this->imageFactory->create($width, $height);
        $edge = new Edge();
        $edge->image = $image;
        $edge->color = $colorGrid;
        $edge->luma = $lumaGrid;
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $edge->x = $x;
                $edge->y = $y;

                // Gathering 3x3 grid data
                $colorGrid->fill($image, $x, $y);
                $lumaGrid->fill($colorGrid);

                // High luma range tells there is an edge in 3x3 box
                if ($lumaGrid->range < max($lumaGrid->max * $this->edgeThreshold, $this->minimumEdgeThreshold)) {
                    // So if there's no such thing, let's just jump to next pixel
                    $target->setColorAt($x, $y, $colorGrid->center);
                    continue;
                }
                EdgeOrientationCalculator::apply($edge);
                EdgeDirectionCalculator::apply($edge);
                EdgeLumaCalculator::apply($edge);
                EdgeDistanceCalculator::apply($edge);
                $offset = SubPixelOffsetCalculator::calculate($edge);
                switch ($this->debug) {
                    case self::DEBUG_EDGE:
                        $alpha = (int) ($offset * 2 * 255);
                        $yellow = 0xFFFF00FF;
                        $yellow = $yellow & ($alpha | 0xFFFFFF00);
                        $color = Color::blend(0xFF0000FF, $yellow);
                        $target->setColorAt($x, $y, $color);
                        break;
                    case self::DEBUG_ORIENTATION:
                        $color = $edge->horizontal ? 0xFFFF00FF : 0x0000FFFF;
                        $target->setColorAt($x, $y, $color);
                        break;
                    case self::DEBUG_PAIR:
                        $target->setColorAt($x, $y, 0x0000FFFF);
                        $value = $edge->inward ? -1 : 1;
                        $xOffset = $edge->horizontal ? 0 : $value;
                        $yOffset = $edge->horizontal ? $value : 0;
                        $target->setColorAt($x + $xOffset, $y + $yOffset, 0x00FF00FF);
                        break;
                    default:
                        $color = TargetColorCalculator::calculate($edge, $offset);
                        $target->setColorAt($x, $y, $color);
                }
            }
        }
        return new Tile($tile->getPosition(), $target);
    }

    public function setDebugMode($mode)
    {
        $this->debug = $mode;
        return $this;
    }
}
