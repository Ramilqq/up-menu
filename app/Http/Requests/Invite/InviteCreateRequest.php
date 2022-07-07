<?php

namespace App\Http\Requests\Invite;

use App\Models\User;
use App\Policies\InvitePolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class InviteCreateRequest extends FormRequest
{
    public function authorize()
    {
        return InvitePolicy::requestCreate($this->id);
    }

    public function rules()
    {
        return [
            'uuid' => ['required', 'unique:invites,uuid'],
            'project_id' => ['required', 'numeric', 'exists:projects,id'],
            'inviter_id' => ['required', 'numeric', 'exists:users,id'],
            'invitee_id' => ['required', 'numeric', 'exists:users,id'],
            'type' => ['required', 'string', 'min:1', 'max:64', 
                request()->user()->role === User::OWNER ? Rule::in([User::ADMIN, User::USER]) : Rule::in([User::USER]),
            ],
            'email' => ['email'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'project_id' => $this->id,
            'uuid' => Str::uuid()->toString(),
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
