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

    public function __construct(Auction $model)
    {

        $this->model = $model;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $data['expires_at'] = Carbon::now()->addMinute(env('APP_AUCTION_EXPIRES'));
        return parent::create($data);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function auctionable()
    {
        return $this->model->auctionable()->get();
    }
}