<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    protected $fillable = ['room_id', 'expires_at'];

    protected $dates = ['expires_at'];

    protected $appends = ['time_left', 'has_expired'];

    /**
     * Get the room for this auction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo('App\Models\Room');
    }

    /**
     * Get the lists of bid for this auction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bids()
    {
        return $this->hasMany('App\Models\Bid');
    }

    /**
     * Get the latest bid received for auction
     *
     * @return mixed
     */
    public function latestBid()
    {
        return $this->hasOne('App\Models\Bid')->accepted()->latest();
    }

    /**
     * Get lists of auctionable rooms
     *
     * @param $query
     * @return mixed
     */
    public function scopeAuctionable($query)
    {
        return $query->where('expires_at', '>=', Carbon::now())->orderBy('expires_at');
    }

    /**
     * Check if auction has expired
     *
     * @return bool
     */
    public function getHasExpiredAttribute()
    {
        return Carbon::now() >= $this->expires_at;
    }

    /**
     * Get the difference between expired time and current time
     *
     * @return mixed
     */
    public function getTimeleftAttribute()
    {
        return $this->expires_at != null ? $this->expires_at->diffForHumans(Carbon::now(), true) : null;
    }

}
