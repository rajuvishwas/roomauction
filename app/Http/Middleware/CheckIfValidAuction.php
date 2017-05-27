<?php

namespace App\Http\Middleware;

use App\Http\Traits\RedirectRequests;
use Closure;
use App\Repositories\Contracts\AuctionRepositoryInterface as Auction;

class CheckIfValidAuction
{

    use RedirectRequests;

    /**
     * @var Auction
     */
    private $auctionRepository;

    public function __construct(Auction $auctionRepository)
    {

        $this->auctionRepository = $auctionRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auctionId = $this->getAuctionId($request);
        $auction = $this->auctionRepository->find($auctionId);

        if ($auction == null) {

            return $this->sendErrorResponse(
                'auctions',
                'Auction does not exist. Please bid on another auction.'
            );

        } else if ($auction->has_expired & $request->route()->getName() != 'bids.show') {

            return $this->sendInfoResponse(
                'auctions',
                'Auction has expired. Please bid on another auction.'
            );
        }

        $request->attributes->add(['auction' => $auction]);
        return $next($request);
    }

    /**
     * @param $request
     * @return mixed
     */
    private function getAuctionId($request)
    {
        return $request->route('auction') != null ? $request->route('auction') : $request->input('auction_id');
    }
}