@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">

                <div class="panel panel-default">
                    <div class="panel-heading">Auction Details</div>

                    <div class="panel-body">

                        <div class="row">
                            <label for="name" class="col-md-2 text-right">Room</label>
                            <div class="col-md-6">{{ $auction->room->name }}</div>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-2 text-right">Time Remaining</label>
                            <div class="col-md-6">{{ $auction->time_left }}</div>
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
                    <div class="panel-heading">Place Bid</div>

                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('bids.store') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="auction_id" value="{{ $auction->id }}"/>

                            <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                                <label for="price" class="col-md-2 control-label">Price</label>

                                <div class="col-md-4">

                                    <div class="input-group">
                                        <span class="input-group-addon">{{ config('app.currency_symbol') }}</span>
                                        <input id="price" type="text" class="form-control" name="price"
                                               value="{{ old('price') }}" autofocus>
                                    </div>

                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-4 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">
                                        Place Bid
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Current Bids</div>

                    <div class="panel-body">

                        <table class="table">
                            <thead class="thead-inverse">
                            <tr>
                                <th width="10%">ID</th>
                                <th>Partner</th>
                                <th width="20%">Bid</th>
                                <th width="20%">Status</th>
                            </tr>
                            </thead>
                            @if($bids->count() != 0)
                                <tbody>
                                @foreach($bids as $row)
                                    <tr class="{{ $auction->latestBid != null && $row->id == $auction->latestBid->id ? 'success' : '' }}">
                                        <th scope="row">{{ $row->id }}</th>
                                        <td>{{ $row->user->name }}</td>
                                        <td>{{ $row->display_price }}</td>
                                        <td>
                                            <span class="label label-{{ $row->is_accepted ? 'success':'danger' }}">
                                                {{ $row->is_accepted ? 'Accepted':'Rejected' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tbody>
                                <tr>
                                    <td colspan="3">No Results Found
                                    </th>
                                </tr>
                                </tbody>
                            @endif
                        </table>

                        @if($bids->count() != 0)
                            {{ $bids->links() }}
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
