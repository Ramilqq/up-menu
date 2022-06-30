<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserCreateRequest extends FormRequest
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
            'phone' => ['required', 'unique:users,phone', 'regex:/^(\+)([0-9]*)$/', 'max:16'],
            'password' => ['required', 'string', 'confirmed'],
            'ip' => ['required', 'string'],
            'role' => ['required', 'string', 'in:'.User::ADMIN.','.User::USER],
            'avatar' => ['image'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ip' => request()->ip(),
            'project_id' => request()->user()->project_id ?: '',
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
