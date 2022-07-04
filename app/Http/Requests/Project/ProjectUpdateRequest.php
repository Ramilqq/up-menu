<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            
            'name' => ['required', 'string', 'min:1', 'max:32'],
            'alias' => ['required', 'unique:projects,alias,'.$this->id],
            'logo' => ['image', 'dimensions:max_width=1000,max_height=1000'],
            'active' => ['required', 'boolean'],
        ];

        switch ($this->getMethod())
        {
            case 'PATCH':
                return $rules;
            case 'PUT':
                return [
                    'user_id' => ['required', 'numeric', 'exists:users,id'],
                ] + $rules;
        }

    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => request()->user()->id
        ]);
    }

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
         'success'   => false,
         'message'   => 'Validation errors',
         'data'      => $validator->errors()
       ])->setStatusCode(400));
    }
}
