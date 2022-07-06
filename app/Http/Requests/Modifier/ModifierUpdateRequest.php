<?php

namespace App\Http\Requests\Modifier;

use App\Models\Menu;
use App\Models\Modifier;
use App\Policies\ModifierPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ModifierUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return ModifierPolicy::requestUpdate($this->id);
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
                    'price' => ['numeric'],
                ];
            case 'PUT':
                return [
                    'name' => ['required', 'string', 'min:1', 'max:32'],
                    'order' => ['required', 'numeric', 'min:0', 'max:32767'],
                    'active' => ['required', 'boolean'],
                    'price' => ['required', 'numeric'],
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
