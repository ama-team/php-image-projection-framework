<?php

namespace AmaTeam\Image\Projection\Type;

use AmaTeam\Image\Projection\API\SpecificationInterface;
use AmaTeam\Image\Projection\API\Type\ValidatingMappingInterface;
use AmaTeam\Image\Projection\Framework\Validation\Tile\SingleTileValidator;
use AmaTeam\Image\Projection\Framework\Validation\Tile\TileFaceValidator;
use AmaTeam\Image\Projection\Framework\Validation\Tile\TileRowValidator;
use AmaTeam\Image\Projection\Framework\Validation\Tile\TileTreeValidator;

/**
 * Adds validation that expects all faces to have equal layout and tile size.
 */
abstract class AbstractValidatingMapping implements ValidatingMappingInterface
{
    /**
     * @inheritDoc
     */
    public function validate(
        array $tiles,
        SpecificationInterface $specification
    ) {
        $face = sizeof($tiles) > 0 ? reset($tiles) : [];
        $tileSize = $specification->getTileSize();
        $tileSize = $tileSize ?: SizeExtractor::extractTileSize($face);
        $layout = $specification->getLayout();
        $layout = $layout ?: SizeExtractor::calculateLayout($face);
        $tileValidator = new SingleTileValidator($tileSize);
        $rowValidator = new TileRowValidator(
            $layout->getWidth(),
            false,
            $tileValidator
        );
        $faceValidator = new TileFaceValidator(
            $layout->getHeight(),
            false,
            $rowValidator
        );
        $treeValidator = new TileTreeValidator(
            $this->getFaces(),
            $faceValidator
        );
        $treeValidator->validate($tiles);
    }
}
