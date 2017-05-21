<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    /**
     * Get the user for this bid
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the auction for this bid
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auction() {
        return $this->belongsTo('App\Models\Auction');
    }
}
