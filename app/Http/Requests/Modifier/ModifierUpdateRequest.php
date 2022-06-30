<?php

namespace App\Http\Requests\Modifier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ModifierUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:32'],
            'order' => ['numeric'],
            'active' => ['numeric'],
            'price' => ['required', 'numeric'],
            'menu_id' => ['required', 'numeric', 'exists:menus,id'],
        ];
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
}
