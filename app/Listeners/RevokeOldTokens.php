<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;

class RevokeOldTokens
{
    public function __construct()
    {
        //
    }

    public function handle(AccessTokenCreated $event)
    {
        //$event->userId, $event->tokenId, $event->clientId;
        $user_tokens = Token::query()->where('user_id', $event->userId)->where('revoked', 0)->where('id', '!=', $event->tokenId)->get();
        if( count($user_tokens) >= User::MAX_SEANS ){
            $tokenRepository = app(TokenRepository::class);
            $refreshTokenRepository = app(RefreshTokenRepository::class);
            
            foreach ($user_tokens as $token) {
                $tokenRepository->revokeAccessToken($token->id);
                $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
            }
        }
    }
}
