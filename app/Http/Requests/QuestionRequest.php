<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
        if ($this->routeIs('questions.index')) 
        {
            return [
                'tags' => 'string'
            ];
        }
        if ($this->routeIs('questions.store') || $this->routeIs('questions.update')) 
        {
            return [
                'title' => 'required',
                'body' => 'required',
                'tags' => 'required|array',
                'tags.*' => 'required|distinct'
            ];
        }
    }
}
