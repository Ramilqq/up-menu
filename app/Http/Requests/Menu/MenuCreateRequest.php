<?php

namespace App\Http\Requests\Menu;

use App\Policies\MenuPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class MenuCreateRequest extends FormRequest
{
    public function authorize()
    {
        return MenuPolicy::requestCreate($this->id);
    }

    public function rules()
    {
        return [
            'order' => ['required', 'numeric', 'min:0', 'max:32767'],
            'project_id' => ['required', 'numeric', 'exists:projects,id'],
            'name' => ['required', 'string', 'min:1', 'max:32'],
            'active' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'project_id' => $this->id,
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
