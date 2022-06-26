<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserUpdateRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email,'.$this->id],
            'phone' => ['required', 'unique:users,phone,'.$this->id, 'regex:/^(\+)([0-9]*)$/', 'max:16'],
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
        ]);
    }

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
         'success'   => false,
         'message'   => 'Validation errors',
         'data'      => $validator->errors()
       ]));
    }
}
