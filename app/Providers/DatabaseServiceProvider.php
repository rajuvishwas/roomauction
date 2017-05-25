<?php

namespace App\Providers;

use App\Repositories\Contracts\AuctionRepositoryInterface;
use App\Repositories\Contracts\RoomRepositoryInterface;
use App\Repositories\Eloquent\AuctionRepository;
use App\Repositories\Eloquent\RoomRepository;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RoomRepositoryInterface::class, RoomRepository::class);
        $this->app->bind(AuctionRepositoryInterface::class, AuctionRepository::class);
    }
}
