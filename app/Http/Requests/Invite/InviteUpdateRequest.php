<?php

namespace App\Http\Requests\Invite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InviteUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'project_id' => ['required', 'numeric', 'exists:projects,id'],
            'inviter_id' => ['required', 'numeric'],
            'invitee_id' => ['required', 'numeric'],
            'type' => ['required', 'string', 'min:1', 'max:64'],
            'email' => ['required', 'email', 'exists:users,email'],
        ];
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
}
