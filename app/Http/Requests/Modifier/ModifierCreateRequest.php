<?php

namespace App\Http\Requests\Modifier;

use App\Policies\ModifierPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ModifierCreateRequest extends FormRequest
{
    public function authorize()
    {
        return ModifierPolicy::requestCreate($this->id);
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:32'],
            'order' => ['required', 'numeric', 'min:0', 'max:32767'],
            'active' => ['required', 'boolean'],
            'price' => ['required', 'numeric'],
            'menu_id' => ['required', 'numeric', 'exists:menus,id'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'menu_id' => $this->id,
            'active' => true,
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
