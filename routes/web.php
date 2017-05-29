<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function() {

    Route::resource('rooms', 'RoomController');

    Route::resource('auctions', 'AuctionController', ['except' => [
        'edit', 'update', 'destroy'
    ]]);

    Route::post('bids', 'BidController@store')->name('bids.store');

    Route::get('auctions/{auction}/{bid}', 'BidController@show')->name('bids.show');

});

Auth::routes();
