<?php

namespace App\Http\Requests\Category;

use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return CategoryPolicy::requestUpdate($this->id);
    }

    public function rules()
    {
        switch ($this->getMethod())
        {
            case 'PATCH':
                return [
                    'name' => ['string', 'min:1', 'max:32'],
                    'order' => ['numeric', 'min:0', 'max:32767'],
                    'active' => ['boolean'],
                    'menu_id' => ['numeric', 'exists:menus,id'],
                ];
            case 'PUT':
                return [
                    'name' => ['required', 'string', 'min:1', 'max:32'],
                    'order' => ['required', 'numeric', 'min:0', 'max:32767'],
                    'active' => ['required', 'boolean'],
                    'menu_id' => ['required', 'numeric', 'exists:menus,id'],
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
