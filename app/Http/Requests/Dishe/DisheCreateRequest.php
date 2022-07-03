<?php

namespace App\Http\Requests\Dishe;

use App\Models\Menu;
use App\Models\ProjectUser;
use App\Policies\DishePolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DisheCreateRequest extends FormRequest
{
    public function authorize()
    {
        return DishePolicy::requestCreate($this->id);
    }

    public function rules()
    {
        $rules = [
            'menu_id' => ['required', 'numeric', 'exists:menus,id'],
            'category_id' => ['required', 'string', 'exists:categories,id'],
            'name' => ['required', 'string', 'min:1', 'max:32'],
            'description' => ['string', 'min:1', 'max:255'],
            'price' => ['required', 'numeric'],
            'order' => ['required', 'numeric', 'min:-128', 'max:127'],
            'active' => ['required', 'boolean'],
            'kbju' => ['numeric'],
            'weight' => [ 'numeric'],
            'calories' => ['numeric'],
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'menu_id' => $this->id,
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
