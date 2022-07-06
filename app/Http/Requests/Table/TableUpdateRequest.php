<?php

namespace App\Http\Requests\Table;

use App\Models\Table;
use App\Policies\TablePolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TableUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return TablePolicy::requestUpdate($this->id);
    }

    public function rules()
    {
        switch ($this->getMethod())
        {
            case 'PATCH':
                return [
                    'name' => ['string', 'min:1', 'max:32'],
                    'active' => ['boolean'],
                    'order' => ['numeric', 'min:0', 'max:32767'],
                ];
            case 'PUT':
                return [
                    'name' => ['required', 'string', 'min:1', 'max:32'],
                    'active' => ['required', 'boolean'],
                    'order' => ['required', 'numeric', 'min:0', 'max:32767'],
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
