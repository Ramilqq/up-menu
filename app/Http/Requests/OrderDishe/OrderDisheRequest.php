<?php

namespace App\Http\Requests\OrderDishe;

use App\Policies\OrderPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class OrderDisheRequest extends FormRequest
{
    public function authorize()
    {
        return OrderPolicy::requestUpdate($this->id);
    }

    public function rules()
    {
        switch ($this->getMethod())
        {
            case 'PATCH':
                return [
                    'order_id' => ['required', 'numeric', 'exists:orders,id'],
                    'dishes_id' => ['required', 'array', 'exists:dishes,id'],
                ];
            case 'DELETE':
                return [
                    'order_id' => ['required', 'numeric', 'exists:orders,id'],
                    'dishes_id' => ['required', 'array', 'exists:dishes,id'],
                ];
        }
    }

    protected function prepareForValidation()
    {    
        $this->merge([
            'order_id' => $this->id,
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
