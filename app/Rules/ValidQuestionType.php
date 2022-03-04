<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidQuestionType implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!in_array($value, ['text', 'textarea', 'select', 'radio', 'checkbox'])) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute needs to be in [text, textarea, select, radio, checkbox]';
    }
}