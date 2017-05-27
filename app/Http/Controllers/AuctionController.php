<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionRequest;
use App\Repositories\Contracts\BidRepositoryInterface as Bid;
use App\Repositories\Contracts\AuctionRepositoryInterface as Auction;
use App\Repositories\Contracts\RoomRepositoryInterface as Room;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * @var Auction
     */
    private $auctionRepository;

    /**
     * @var Room
     */
    private $roomRepository;

    /**
     * @var Bid
     */
    private $bidRepository;

    public function __construct(Auction $auctionRepository, Room $roomRepository, Bid $bidRepository)
    {
        $this->middleware('auth.admin')->except('index', 'show');
        $this->middleware('auction')->only('show');
        $this->auctionRepository = $auctionRepository;
        $this->roomRepository = $roomRepository;
        $this->bidRepository = $bidRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auctions = $this->auctionRepository->auctionable();
        return view('auctions.index', compact('auctions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rooms = $this->roomRepository->inactive();
        return view('auctions.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AuctionRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuctionRequest $request)
    {
        $this->auctionRepository->create($request->all());

        return redirect('auctions')
            ->with('status', 'Auction has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {

        $auction = request()->get('auction');
        $bids = $this->bidRepository->findAllByAuction($auction->id);

        return view('auctions.show', compact('auction', 'bids'));
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
    public function update(Request $request, $id)
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
}
