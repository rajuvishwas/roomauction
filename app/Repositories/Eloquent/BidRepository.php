<?php

namespace App\Repositories\Eloquent;

use App\Models\Bid;
use App\Repositories\Contracts\BidRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BidRepository extends Repository implements BidRepositoryInterface
{

    /**
     * @var Bid
     */
    protected $model;

    public function __construct(Bid $model)
    {

        $this->model = $model;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $data['user_id'] = Auth::user()->id;
        return parent::create($data);
    }

    /**
     * @param $auctionId
     * @return \App\Pagination\CursorPaginator
     */
    public function findAllByAuction($auctionId)
    {
        $filters = array(
            'auction_id' => $auctionId
        );

        return $this->cursorPaginate(
            config('app.results_per_page'),
            array('*'),
            'created_at',
            'desc',
            $filters
        );
    }
}