<?php

namespace AmaTeam\Image\Projection\Framework\Validation;

use LogicException;

/**
 * Designed to be thrown whenever validation fails
 */
class ValidationException extends LogicException
{
    /**
     * @inheritDoc
     */
    public function __construct($violation, array $path = [], $previous = null)
    {
        $message = sprintf('%s: %s', self::renderPath($path), $violation);
        parent::__construct($message, 0, $previous);
    }

    private static function renderPath(array $path)
    {
        return implode('.', array_merge(['$'], $path));
    }
}
