<?php

namespace App\Http\Requests\Menu;

use App\Models\Menu;
use App\Policies\MenuPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MenuUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return MenuPolicy::requestUpdate($this->id);
    }

    public function rules()
    {
        return [
            'project_id' => ['required', 'numeric', 'exists:projects,id'],
            'name' => ['required', 'string', 'min:1', 'max:32'],
            'active' => ['required', 'boolean'],
            'order' => ['required', 'numeric', 'min:-128', 'max:127'],
        ];
    }

    protected function prepareForValidation()
    {
        $project_id = Menu::getMenuProjectId($this->id);
        $this->merge([
            'project_id' => $project_id,
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
