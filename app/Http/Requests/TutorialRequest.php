<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TutorialRequest extends FormRequest
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
        if ($this->routeIs('tutorials.index'))
		{
			return [
				'tags' => 'string'
			];
		}
		else if ($this->routeIs('tutorials.store') )
		{
			return [
				'body' => 'required',
				'tags' => 'required|array',
                'tags.*' => 'required|distinct',
                'files' => 'array',
				'files.*' => 'file|max:51200'
			];
		}
    }
}
