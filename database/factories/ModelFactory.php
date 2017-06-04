<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'role_id' => 2,
        'callback_url' => $faker->url,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Role::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name
    ];
});

$factory->define(App\Models\Room::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'min_bid' => $faker->numberBetween(100, 500)
    ];
});

$factory->define(App\Models\Auction::class, function (Faker\Generator $faker) {

    return [
        'room_id' => function () {
            return factory('App\Models\Room')->create()->id;
        },
        'expires_at' => \Carbon\Carbon::now()->addMinute(config('app.auction_expires'))
    ];
});

$factory->define(App\Models\Bid::class, function (Faker\Generator $faker) {

    return [
        'user_id' => function () {
            return factory('App\Models\User')->create()->id;
        },
        'auction_id' => function () {
            return factory('App\Models\Auction')->create()->id;
        },
        'price' => function(array $bid) {
            return App\Models\Auction::find($bid['auction_id'])->room->min_bid;
        },
        'is_accepted' => true
    ];
});
