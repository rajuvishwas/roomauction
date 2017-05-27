@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Auction Details
                        <div class="btn-group pull-right">
                            @if(!$auction->has_expired)
                                <a class="btn btn-primary btn-xs"
                                   href="{{ route('auctions.show', ['id' => $auction->id]) }}"
                                   role="button">Place Bid</a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="row">
                            <label for="name" class="col-md-2 text-right">Room</label>
                            <div class="col-md-6">{{ $auction->room->name }}</div>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 text-right">Time Remaining</label>
                            <div class="col-md-6">
                                @if($auction->has_expired)
                                    <span class="label label-danger">Expired</span>
                                @else
                                    {{ $auction->time_left }}
                                @endif
                            </div>
                        </div>

                        @if($auction->bids_count != 0)
                            <div class="row">
                                <label for="name" class="col-md-2 text-right"># Bids</label>
                                <div class="col-md-6">{{ $auction->bids_count }}</div>
                            </div>
                        @endif

                        @if($auction->latestBid != null)
                            <div class="row">
                                <label for="name" class="col-md-2 text-right">Latest Bid</label>
                                <div class="col-md-6">{{ $auction->latestBid->display_price }}</div>
                            </div>
                        @else
                            <div class="row">
                                <label for="name" class="col-md-2 text-right">Minimum Bid</label>
                                <div class="col-md-6">{{ $auction->room->display_min_bid }}</div>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Bid Details</div>

                    <div class="panel-body">

                        <div class="row">
                            <label for="name" class="col-md-2 text-right">Price</label>
                            <div class="col-md-6">{{ $bid->display_price }}</div>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 text-right">Status</label>
                            <div class="col-md-6">
                                <span class="label label-{{ $bid->is_accepted ? 'success':'danger' }}">
                                    {{ $bid->is_accepted ? 'Accepted':'Rejected' }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                @if($auction->latestBid->id == $bid->id)
                    <div class="alert alert-success text-center">
                        <strong>Congratulations!</strong>
                        @if($auction->has_expired)
                            Your bid is the winner.
                        @else
                            Your bid is winning.
                        @endif
                    </div>
                @else
                    <div class="alert alert-danger text-center">
                        <strong>Sorry!</strong> Your bid is not winner.
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection