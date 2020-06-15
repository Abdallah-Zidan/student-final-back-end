<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoursePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->routeIs('coursePosts.store'))
        {
            return [
                'body'=>'required',
                'course_department_faculty_id'=>'required|integer',
                'files' => 'array',
				'files.*' => 'file|max:51200'
            ];
        
        }
        else if ($this->routeIs('coursePosts.update'))
        {
            return [
                'body'=>'required',
                'files' => 'array',
				'files.*' => 'file|max:51200'
            ];
        }
    }
        
}
