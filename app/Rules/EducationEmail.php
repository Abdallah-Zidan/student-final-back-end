<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EducationEmail implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.+-]+\.edu\.eg$/", $value); // example: xxx@xxxx.edu.eg
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'invalid email format must be like this xxx@xxx.edu.eg';
    }
}
