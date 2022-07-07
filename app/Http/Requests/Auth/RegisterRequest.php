<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max:10'],
            'last_name' => ['required', 'string', 'min:3', 'max:10'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'unique:users,phone', 'regex:/^7([0-9]*)$/', 'max:16'],
            'password' => ['required', 'string', 'confirmed', 'min:3', 'max:30'],
            'ip' => ['required', 'string'],
            'role' => ['required', 'string']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ip' => request()->ip(),
            'role' => User::OWNER,
        ]);
    }

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
         'success'   => false,
         'message'   => 'Validation errors',
         'data'      => $validator->errors(),
         'code' => 400
       ])->setStatusCode(400));
    }

}
