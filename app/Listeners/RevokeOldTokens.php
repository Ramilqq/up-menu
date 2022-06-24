<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Token;

class RevokeOldTokens
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AccessTokenCreated $event)
    {
        //$event->userId, $event->tokenId, $event->clientId;
        $user_tokens = Token::query()->where('user_id', $event->userId)->where('revoked', 0)->where('id', '!=', $event->tokenId)->get();
        if( count($user_tokens) >= User::MAX_SEANS ){
            foreach ($user_tokens as $token) {
                $token->revoke();
            }
        }
    }
}
