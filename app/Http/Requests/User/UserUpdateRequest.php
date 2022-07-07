<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return UserPolicy::requestUpdate($this->id);
    }
    
    public function rules()
    {
        switch ($this->getMethod())
        {
            case 'PATCH':
                return [
                    'first_name' => ['string', 'min:3', 'max:10'],
                    'last_name' => ['string', 'min:3', 'max:10'],
                    'email' => ['email', 'unique:users,email,'.$this->id],
                    'phone' => ['unique:users,phone,'.$this->id, 'regex:/^7([0-9]*)$/', 'max:16'],
                    'password' => ['string', 'confirmed', 'min:3', 'max:30'],
                    'ip' => ['string'],
                    'role' => ['string', 'in:'.User::ADMIN.','.User::USER],
                    'avatar' => ['image'],
                ];
            case 'PUT':
                return [
                    'first_name' => ['required', 'string', 'min:3', 'max:10'],
                    'last_name' => ['required', 'string', 'min:3', 'max:10'],
                    'email' => ['required', 'email', 'unique:users,email,'.$this->id],
                    'phone' => ['required', 'unique:users,phone,'.$this->id, 'regex:/^7([0-9]*)$/', 'max:16'],
                    'password' => ['required', 'string', 'confirmed', 'min:3', 'max:30'],
                    'ip' => ['required', 'string'],
                    'role' => ['required', 'string', 'in:'.User::ADMIN.','.User::USER],
                    'avatar' => ['required', 'image'],
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
