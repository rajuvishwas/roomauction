<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $fillable = ['user_id', 'auction_id', 'price', 'is_accepted'];

    protected $appends = ['display_price'];

    /**
     * Get the user for this bid
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the auction for this bid
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auction()
    {
        return $this->belongsTo('App\Models\Auction');
    }

    /**
     * @return string
     */
    public function getDisplayPriceAttribute()
    {
        return config('app.currency_symbol') .' '. $this->price;
    }

    public function scopeAccepted($query) {

        return $query->where('is_accepted', true);
    }
}
