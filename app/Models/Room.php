<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * Get the lists of auction for this room
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function auctions() {
        return $this->hasMany('App\Models\Auction');
    }
}
