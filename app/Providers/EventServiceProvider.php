<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        'Illuminate\Auth\Events\Registered' => [
            //\Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
            'App\Listeners\SendEmailVerificationListener',
        ],
        'App\Events\EmployeeCreatedEvent' => [
            'App\Listeners\SendEmailEmployeeLoginListener',
        ],

        'Laravel\Passport\Events\AccessTokenCreated' => [
            'App\Listeners\RevokeOldTokens',
        ],
     
        'Laravel\Passport\Events\RefreshTokenCreated' => [
            //'App\Listeners\PruneOldTokens',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //Event::listen(
            //\Illuminate\Auth\Events\Registered::class,
            //\Laravel\Passport\Events\AccessTokenCreated::class
        //);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
