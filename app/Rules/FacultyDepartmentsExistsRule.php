<?php

namespace App\Rules;

use App\DepartmentFaculty;
use Illuminate\Contracts\Validation\Rule;

class FacultyDepartmentsExistsRule implements Rule
{
    private $faculty;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($faculty)
    {
        $this->faculty = $faculty;
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
        $department_faculty = DepartmentFaculty::where([
            ['department_id', $value],
            ['faculty_id', $this->faculty]
        ])->first();

        return $department_faculty ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This Department does not belong to this Faculty';
    }
}