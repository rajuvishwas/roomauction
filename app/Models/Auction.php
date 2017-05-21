<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    /**
     * Get the room for this auction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room() {
        return $this->belongsTo('App\Models\Room');
    }

    /**
     * Get the lists of bid for this auction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bids() {
        return $this->hasMany('App\Models\Bid');
    }
}
