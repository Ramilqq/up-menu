<?php

namespace App\Http\Requests\Order;

use App\Models\Order;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class OrderCreateRequest extends FormRequest
{
    public function authorize()
    {
        return OrderPolicy::requestCreate($this->id);
    }

    public function rules()
    {
        return [
            'project_id' => ['required', 'numeric', 'exists:projects,id'],
            'table_id' => ['required', 'numeric', 'exists:tables,id',
                Rule::unique('orders')->where(function ($query) {
                    return $query->where('table_id', $this->table_id)->whereIn('status', [Order::CLOSE, Order::PAID]);
                }),
            ],
            'status' => ['required'],
            'dishes_id' => ['required', 'array', 'exists:dishes,id']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'project_id' => $this->id,
            'table_id' => $this->table_id,
            'status' =>Order::NEW,
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

    public function messages(): array
    {
        return [
            'table_id.unique' => 'Данный стол уже используется',
        ];
    }
}
