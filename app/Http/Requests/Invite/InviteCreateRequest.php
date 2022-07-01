<?php

namespace App\Http\Requests\Invite;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class InviteCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'project_id' => ['required', 'numeric', 
                Rule::exists('project_users')->where(function ($query) {
                    return $query->where('project_id', $this->id)->where('user_id', request()->user()->id);
                }),
            ],
            'inviter_id' => ['required', 'numeric', 'exists:users,id'],
            'invitee_id' => ['required', 'numeric', 'exists:users,id'],
            'type' => ['required', 'string', 'min:1', 'max:64', 
                request()->user()->role === User::OWNER ? Rule::in([User::ADMIN, User::USER]) : Rule::in([User::USER]),
            ],
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'project_id' => $this->id,
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
