<?php

namespace AmaTeam\Image\Projection\Framework\Validation\Tile;

use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Framework\Validation\ValidatorInterface;

class TileTreeValidator implements ValidatorInterface
{
    /**
     * @var string[]
     */
    private $faces;
    /**
     * @var ValidatorInterface
     */
    private $faceValidator;

    /**
     * @param string[] $faces
     * @param ValidatorInterface $faceValidator
     */
    public function __construct(
        array $faces,
        ValidatorInterface $faceValidator = null
    ) {
        $this->faces = $faces;
        $this->faceValidator = $faceValidator;
    }

    /**
     * @inheritDoc
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    public function validate($value, array $path = [])
    {
        if (!is_array($value)) {
            throw new ValidationException('Is empty', $path);
        }
        $missingFaces = $this->faces;
        $extraFaces = [];
        foreach ($value as $face => $grid) {
            if (in_array($face, $this->faces)) {
                $missingFaces = array_diff($missingFaces, [$face]);
            } else {
                $extraFaces[] = $face;
            }
            if ($this->faceValidator) {
                $facePath = array_merge($path, [$face]);
                $this->faceValidator->validate($grid, $facePath);
            }
        }
        if (!empty($missingFaces)) {
            $message = 'Following faces were not found: ' .
                implode(', ', $missingFaces);
            throw new ValidationException($message);
        }
        if (!empty($extraFaces)) {
            $message = 'Following extra faces were found: ' .
                implode(', ', $extraFaces);
            throw new ValidationException($message);
        }
    }
}
