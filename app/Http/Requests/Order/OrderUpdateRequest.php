<?php

namespace App\Http\Requests\Order;

use App\Models\Order;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderUpdateRequest extends FormRequest
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
                    'table_id' => ['numeric', 'exists:tables,id'],
                    'user_id' => ['numeric', 'exists:users,id'],
                    'name' => ['string', 'min:1', 'max:32'],
                    'status' => ['string', 'in:'. Order::NEW.','.Order::WORK.','.Order::CLOSE.','.Order::PAID],
                    'sum' => ['numeric'],
                ];
            case 'PUT':
                return [
                    'table_id' => ['required', 'numeric', 'exists:tables,id'],
                    'user_id' => ['required', 'numeric', 'exists:users,id'],
                    'name' => ['required', 'string', 'min:1', 'max:32'],
                    'status' => ['required', 'string', 'in:'. Order::NEW.','.Order::WORK.','.Order::CLOSE.','.Order::PAID],
                    'sum' => ['required', 'numeric'],
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
