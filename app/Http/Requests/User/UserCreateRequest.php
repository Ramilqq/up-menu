<?php

namespace App\Http\Requests\User;

use App\Models\Invite;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class UserCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        
        return [
            'first_name' => ['string', 'min:3', 'max:10'],
            'last_name' => ['string', 'min:3', 'max:10'],
            'phone' => ['required', 'unique:users,phone', 'regex:/^7([0-9]*)$/', 'max:16'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:3', 'max:30'],
            'ip' => ['required', 'string'],
            'role' => ['required', 'string', 'in:'.User::ADMIN.','.User::USER],
            'project_id' => ['required', 'exists:projects,id'],
        ];
    }

    protected function prepareForValidation()
    {
        $uuid = $this->uuid;
        if (!$invite = Invite::query()->where('uuid', $uuid)->where('invitee_id', null)->first()) return;

        $this->merge([
            //'email' => $invite->email ?: '',
            'ip' => request()->ip(),
            'role' => $invite->type ?: '',
            'project_id' => $invite->project_id ?: '',
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

    public function deCryptUuid($uuid)
    {
        try {
            return Crypt::decryptString($this->uuid);
        } catch (\Throwable $th) {
            return null;
        }
    }
}
