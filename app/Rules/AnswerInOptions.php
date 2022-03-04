<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Arr;

class AnswerInOptions implements Rule, DataAwareRule
{
    protected $data = [];

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $answer) {
            if (!in_array($answer, Arr::flatten($this->data['options']))) {
                return false;
            }
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
        return 'The :attribute should contain values found in the options choices';
    }
}


// 'answer' => ['required', function ($attribute, $value, $fail) {
//     $options = Arr::flatten(request('options'));
//     foreach ($value as $answer) {
//         if (!in_array($answer, $options)) {
//             $fail("The {$attribute} should contain values found in the options choices");
//         }
//     }