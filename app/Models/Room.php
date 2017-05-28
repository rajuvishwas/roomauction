<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

    protected $fillable = ['name', 'min_bid'];

    protected $appends = ['display_min_bid'];

    /**
     * Get the lists of auction for this room
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function auctions()
    {
        return $this->hasMany('App\Models\Auction');
    }

    /**
     * Get the price with currency symbol
     *
     * @return string
     */
    public function getDisplayMinBidAttribute()
    {
        return config('app.currency_symbol') . ' ' . $this->min_bid;
    }
}
