<?php

namespace App\Http\Requests\Project;

use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return ProjectPolicy::requestUpdate($this->id);
    }

    public function rules()
    {
        switch ($this->getMethod())
        {
            case 'PATCH':
                return [
                    'name' => ['string', 'min:1', 'max:32'],
                    'alias' => ['unique:projects,alias,'.$this->id, 'regex:/^[a-z0-9_]+$/'],
                    'logo' => ['image', 'dimensions:max_width=1000,max_height=1000'],
                    'active' => ['boolean'],
                ];
            case 'PUT':
                return [
                    'name' => ['required', 'string', 'min:1', 'max:32'],
                    'alias' => ['required', 'unique:projects,alias,'.$this->id, 'regex:/^[a-z0-9_]+$/'],
                    'logo' => ['required', 'image', 'dimensions:max_width=1000,max_height=1000'],
                    'active' => ['required', 'boolean'],
                ];
        }
    }

    protected function prepareForValidation()
    {
        $this->merge([
            //
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

    public function failedAuthorization() {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Authorization errors',
          ])->setStatusCode(401));
    }
}
