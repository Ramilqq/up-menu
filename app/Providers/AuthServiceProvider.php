<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensCan([
            User::USER => 'User role',
            User::ADMIN => 'User role',
            User::OWNER => 'User role',
        ]);

        Passport::tokensExpireIn(now()->addMinute(30));
        Passport::refreshTokensExpireIn(now()->addDays(60));

        /*VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)->markdown('vendor/notifications/email', [
                'greeting' => 'Привет!',
                'actionText' => 'Подтвердить',
                'actionUrl' => $url,
                'introLines' => [
                    'Перейдите по ссылке для подверждения почты',
                ],
                //'salutation' => $this->url,
                'displayableActionUrl' => 'Ссылка',
            ]);
        });*/

        
    }
}
