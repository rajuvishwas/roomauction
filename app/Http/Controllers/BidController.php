<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\AuctionRepositoryInterface as Auction;
use App\Repositories\Contracts\BidRepositoryInterface as Bid;
use App\Http\Requests\BidRequest;
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

    public function __construct(Bid $bidRepository, Auction $auctionRepository)
    {
        $this->middleware('auction');
        $this->middleware('auth.admin')->except('store', 'show');
        $this->bidRepository = $bidRepository;
        $this->auctionRepository = $auctionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BidRequest|Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(BidRequest $request)
    {
        $auction = $this->auctionRepository->find($request->input('auction_id'));

        if ($auction == null) {

            return $this->sendErrorResponse(
                'auctions',
                'Auction does not exist. Please bid on another auction.'
            );

        } else if ($auction->has_expired) {

            return $this->sendInfoResponse(
                'auctions',
                'Auction has expired. Please bid on another auction.'
            );
        }

        $isBidAccepted = true;
        if ($auction->latestBid != null && $this->isBidRejected($request->input('price'), $auction->latestBid->price)) {
            $isBidAccepted = false;
        }

        $data = $request->all();
        $data['is_accepted'] = $isBidAccepted;
        $this->bidRepository->create($data);

        if ($isBidAccepted) {
            return redirect()
                ->route('auctions.show', ['id' => $auction->id])
                ->with('status', 'Your bid has been placed.');
        } else {
            return redirect()
                ->route('auctions.show', ['id' => $auction->id])
                ->with('error', 'Your bid was not accepted. Your bid should be above ' . config('app.bid_accepted_percent') . '% of latest bid.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BidRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $newPrice
     * @param $oldPrice
     * @return bool
     */
    private function isBidRejected($newPrice, $oldPrice)
    {
        $newPrice = floatval($newPrice);
        $oldPrice = floatval($oldPrice);

        return $newPrice < ($oldPrice + ($oldPrice * (config('app.bid_accepted_percent') / 100)));
    }
}
