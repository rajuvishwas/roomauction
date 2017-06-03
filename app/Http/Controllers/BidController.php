<?php

namespace App\Http\Controllers;

use App\Http\Requests\BidRequest;
use App\Jobs\SendOutbidNotification;
use App\Repositories\Contracts\AuctionRepositoryInterface as Auction;
use App\Repositories\Contracts\BidRepositoryInterface as Bid;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{

    /**
     * @var Bid
     */
    private $bidRepository;

    /**
     * @var Auction
     */
    private $auctionRepository;

    /**
     * BidController constructor.
     * @param Bid $bidRepository
     * @param Auction $auctionRepository
     */
    public function __construct(Bid $bidRepository, Auction $auctionRepository)
    {
        $this->middleware('auth.admin')->except('store', 'show');
        $this->middleware('auction')->only('store', 'show');

        $this->bidRepository = $bidRepository;
        $this->auctionRepository = $auctionRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request|BidRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(BidRequest $request)
    {
        $auction = request()->get('auction');

        $isBidAccepted = $this->isBidAccepted(
            $request->input('price'),
            $auction->latestBid
        );

        $data = $request->all();
        $data['is_accepted'] = $isBidAccepted;
        $bid = $this->bidRepository->create($data);

        if ($isBidAccepted) {

            if ($this->isLastMinuteBid($auction->expires_at)) {

                $auctionData['expires_at'] = $auction->expires_at
                    ->addMinute(config('app.bid_lastminute_extend'));

                $this->auctionRepository->update($auctionData, $auction->id);
            }

            if ($auction->latestBid != null)
                dispatch(new SendOutbidNotification($auction->latestBid, $bid));

            return $this->sendSuccessResponse(
                'Your bid has been placed.',
                'bids.show',
                [
                    'auction' => $auction->id,
                    'bid' => $bid->encoded_key
                ]
            );

        } else {

            return $this->sendErrorResponse(
                'Your bid was not accepted. Your bid should be above ' . config('app.bid_accepted_percent') . '% of latest bid.',
                'auctions.show',
                [
                    'auction' => $auction->id
                ]
            );
        }

    }

    /**
     * Display the specified resource.
     *
     * @param $auctionId
     * @param $bidId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show($auctionId, $bidId)
    {

        $auction = request()->get('auction');
        $bidId = $this->bidRepository->decode($bidId);
        if ($bidId != "") {

            $bid = $this->bidRepository->find($bidId);
            if ($bid->user_id == Auth::user()->id) {

                return view(
                    'bids.show',
                    compact('auction', 'bid')
                );

            }

            return $this->sendErrorResponse(
                'Unauthorized Access',
                'home'
            );
        }

        return $this->sendErrorResponse(
            'Unauthorized Access',
            'home'
        );
    }

    /**
     * Check if bid is accepted when new price less than
     * bid acceptance percentage of old price
     *
     * @param $newPrice
     * @param $oldBid
     * @return bool
     */
    private function isBidAccepted($newPrice, $oldBid)
    {
        if ($oldBid == null)
            return true;

        $newPrice = floatval($newPrice);
        $oldPrice = floatval($oldBid->price);

        return $newPrice > ($oldPrice + ($oldPrice * (config('app.bid_accepted_percent') / 100)));
    }


    /**
     * Check if last minute bid
     *
     * @param $expires_at
     * @return bool
     */
    private function isLastMinuteBid($expires_at)
    {
        return Carbon::now()->diffInSeconds($expires_at, false) <= 60 ? true : false;
    }
}
