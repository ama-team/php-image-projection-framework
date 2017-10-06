<?php

namespace AmaTeam\Image\Projection\Framework\Validation\Tile;

use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Framework\Validation\ValidatorInterface;

class TileRowValidator implements ValidatorInterface
{
    /**
     * @var int|null
     */
    private $size;
    /**
     * @var bool
     */
    private $allowedEmpty = false;
    /**
     * @var ValidatorInterface|null
     */
    private $tileValidator;

    /**
     * @param int|null $size
     * @param bool $allowedEmpty
     * @param ValidatorInterface|null $tileValidator
     */
    public function __construct(
        $size = null,
        $allowedEmpty = false,
        ValidatorInterface $tileValidator = null
    ) {
        $this->size = $size;
        $this->allowedEmpty = $allowedEmpty;
        $this->tileValidator = $tileValidator;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, array $path = [])
    {
        if (!is_array($value)) {
            throw new ValidationException('Is not an array', $path);
        }
        if (!$this->allowedEmpty && empty($value)) {
            throw new ValidationException('Is empty array', $path);
        }
        if (is_int($this->size) && sizeof($value) !== $this->size) {
            $template = 'Has size of %d (%d expected)';
            $message = sprintf($template, sizeof($value), $this->size);
            throw new ValidationException($message);
        }
        if ($this->tileValidator) {
            foreach ($value as $offset => $tile) {
                $tilePath = array_merge($path, [$offset]);
                $this->tileValidator->validate($tile, $tilePath);
            }
        }
    }
}
