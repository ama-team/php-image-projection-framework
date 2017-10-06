<?php

namespace AmaTeam\Image\Projection\Framework\Validation\Tile;

use AmaTeam\Image\Projection\Framework\Validation\ValidationException;
use AmaTeam\Image\Projection\Framework\Validation\ValidatorInterface;

class TileFaceValidator implements ValidatorInterface
{
    /**
     * @var int|null
     */
    private $height;
    /**
     * @var bool
     */
    private $allowedEmpty = false;
    /**
     * @var ValidatorInterface|null
     */
    private $rowValidator = null;

    /**
     * @param int|null $height
     * @param bool $allowedEmpty
     * @param ValidatorInterface|null $rowValidator
     */
    public function __construct(
        $height = null,
        $allowedEmpty = false,
        $rowValidator = null
    ) {
        $this->height = $height;
        $this->allowedEmpty = $allowedEmpty;
        $this->rowValidator = $rowValidator;
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
            throw new ValidationException('Is empty', $path);
        }
        if (is_int($this->height) && sizeof($value) !== $this->height) {
            $template = 'Height is %d (%d expected)';
            $message = sprintf($template, sizeof($value), $this->height);
            throw new ValidationException($message, $path);
        }
        if ($this->rowValidator) {
            foreach ($value as $y => $row) {
                $rowPath = array_merge($path, [$y]);
                $this->rowValidator->validate($row, $rowPath);
            }
        }
    }
}
