<?php

namespace App\Http\Requests\Table;

use App\Policies\TablePolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TableCreateRequest extends FormRequest
{
    public function authorize()
    {
        return TablePolicy::requestCreate($this->id);
    }

    public function rules()
    {
        return [
            'project_id' => ['required', 'numeric', 'exists:projects,id'],
            'name' => ['required', 'string', 'min:1', 'max:32'],
            'uuid' => ['required', 'unique:tables,uuid'],
            'active' => ['required', 'boolean'],
            'order' => ['required', 'numeric', 'min:0', 'max:32767'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'project_id' => $this->id,
            'uuid' => Str::uuid()->toString(),
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
