<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class MenuCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'uuid' => ['required', 'unique:menus,uuid'],
            'project_id' => ['required', 'numeric', 'exists:projects,id'],
            'name' => ['required', 'string', 'min:1', 'max:32'],
            'active' => ['numeric'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'uuid' => Str::uuid()->toString(),
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
