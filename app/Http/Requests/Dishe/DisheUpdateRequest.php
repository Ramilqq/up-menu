<?php

namespace App\Http\Requests\Dishe;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DisheUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'menu_id' => ['required', 'numeric', 'exists:menus,id'],
            'category_id' => ['required', 'string', 'exists:categories,id'],
            'name' => ['required', 'string', 'min:1', 'max:32'],
            'description' => ['string', 'min:1', 'max:255'],
            'price' => ['required', 'numeric'],
            'photo' => ['image', 'dimensions:max_width=1000,max_height=1000'],
            'order' => ['required', 'numeric', 'min:0', 'max:32767'],
            'active' => ['required', 'boolean'],
            'kbju' => ['numeric'],
            'weight' => [ 'numeric'],
            'calories' => ['numeric'],
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
