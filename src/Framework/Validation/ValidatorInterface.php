<?php

namespace AmaTeam\Image\Projection\Framework\Validation;

/**
 * Abstract validator interface that is focused on validating single value.
 */
interface ValidatorInterface
{
    /**
     * @param mixed $value
     * @param array $path
     * @throws ValidationException
     * @return void
     */
    public function validate($value, array $path = []);
}
