<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\PasswordOverrideGrant;
use League\OAuth2\Server\AuthorizationServer;
use App\Helpers\UserRepositoryOverride;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $server = $this->app->make(AuthorizationServer::class);
        /** custom grant type */
        $server->enableGrantType(
          new PasswordOverrideGrant(
            $this->app->make(UserRepositoryOverride::class),
            $this->app->make(RefreshTokenRepository::class)
          ), Passport::tokensExpireIn()
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Http\Middleware\EntityLanguage');

    }
}
