<?php

namespace Frankenstein\Validation\Rules;

use Frankenstein\Validation\Rule;

class RequiredRule extends Rule
{
    public function validate($field): bool
    {
        $validation = isset($field);

        if (!$validation) {
            $this->setError("Value cannot be null!");
        }

        return $validation;
    }
}
