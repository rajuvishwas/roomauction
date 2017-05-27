<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('greater_than', function ($attribute, $value, $parameters, $validator) {
            $greater_than = floatval($parameters[0]);
            return $value > $greater_than;
        });

        Validator::replacer('greater_than', function ($message, $attribute, $rule, $parameters) {
            return str_replace(
                '_',
                ' ',
                'The ' . $attribute . ' should be greater than ' . config('app.currency_symbol') . ' ' . $parameters[0] . ''
            );
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
