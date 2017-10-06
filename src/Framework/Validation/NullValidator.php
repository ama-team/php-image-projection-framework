<?php

namespace AmaTeam\Image\Projection\Framework\Validation;

/**
 * @codeCoverageIgnore
 */
class NullValidator implements ValidatorInterface
{
    /**
     * @inheritDoc
     */
    public function validate($value, array $path = [])
    {
        // intentional noop
    }
}
