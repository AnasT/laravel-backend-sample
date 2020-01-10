<?php

namespace App\Providers;

use App\Auth\Client;
use App\Field;
use App\Policies\FieldPolicy;
use App\Policies\SubscriberPolicy;
use App\Subscriber;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Field::class => FieldPolicy::class,
        Subscriber::class => SubscriberPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(function (RouteRegistrar $router) {
            $router->forAuthorization();

            Route::post('/token', [
                'uses' => 'AccessTokenController@issueToken',
                'as' => 'passport.token',
                'middleware' => 'throttle',
            ]);
        });

        Route::post('/oauth/token/revoke', 'App\Http\Controllers\Api\AuthController@revoke')
            ->middleware(['api', 'auth:api']);

        Passport::tokensExpireIn(now()->addHour());
        Passport::refreshTokensExpireIn(now()->addMonth());
        Passport::useClientModel(Client::class);
    }
}
