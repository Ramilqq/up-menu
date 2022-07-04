<?php

namespace App\Http\Requests\Table;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TableCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'project_id' => ['required', 'numeric',
                Rule::exists('project_users')->where(function ($query) {
                    return $query->where('project_id', $this->project_id)->where('user_id', request()->user()->id);
                }),
            ],
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
}
