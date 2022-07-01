<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{

    function __construct()
    {
        
    }

    public function login(LoginRequest $request)
    {
        $client = Client::query()->where('password_client', 1)->first();

        $response = Http::asForm()->post( url('/').'/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ]);
        if ($response->status() !== 200)  return response()->json(['success' => false, 'message' => __('auth.failed')], 400);
        return response()->json([
            'success' => true,
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'token_type' => $response['token_type'],
        ])->withCookie('refresh_token', $response['refresh_token'], 43200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => __('logout'),
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        if (!$user = User::create($data))
        {
            return response()->json(['success' => false], 400);
        }
        event(new \Illuminate\Auth\Events\Registered($user));
        return response()->json($user, 200);
    }

    public function token(Request $request)
    {
        $client = Client::query()->where('password_client', 1)->first();

        $response = Http::asForm()->post( url('/').'/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->cookie('refresh_token'),
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => '',
        ]);
        if ($response->status() !== 200)  return response()->json(['success' => false, 'message' => __('auth.failed')], 400);
        return response()->json([
            'success' => true,
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'token_type' => $response['token_type'],
        ])->withCookie('refresh_token', $response['refresh_token'], 86400);
    }

    public function restore(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['success' => true, 'message' => __($status)])
            : response()->json(['success' => false, 'message' => __($status)]);
    }

    public function restoreAccount(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:3|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
            
                $user->password = bcrypt($password);
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? response()->json(['success' => true, 'message' => __($status)])
                    : response()->json(['success' => false, 'message' => __($status)]);
    }

}
