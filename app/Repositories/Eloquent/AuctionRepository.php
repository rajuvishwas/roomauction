<?php

namespace App\Repositories\Eloquent;

use App\Models\Auction;
use App\Repositories\Contracts\AuctionRepositoryInterface;
use Carbon\Carbon;

class AuctionRepository extends Repository implements AuctionRepositoryInterface
{
    /**
     * @var Auction
     */
    protected $model;

    /**
     * AuctionRepository constructor.
     * @param Auction $model
     */
    public function __construct(Auction $model)
    {
        $this->model = $model;
    }

    /**
     * Create auction for a room
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $data['expires_at'] = Carbon::now()->addMinute(config('app.auction_expires'));
        return parent::create($data);
    }

    /**
     * Get lists of auctionable rooms and latest bid details
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function auctionable()
    {
        return $this->model->with('latestBid')->withCount('bids')->auctionable()->get();
    }

    /**
     * Get auction details with latest bid
     *
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->with('latestBid')->withCount('bids')->find($id, $columns);
    }
}