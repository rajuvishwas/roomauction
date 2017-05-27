@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Auction
                        @if(Auth::user()->isAdmin())
                            <div class="btn-group pull-right">
                                <a href="{{ route('auctions.create') }}" class="btn btn-primary btn-xs">Add Auction</a>
                            </div>
                        @endif
                    </div>

                    <div class="panel-body">

                        <table class="table">
                            <thead class="thead-inverse">
                            <tr>
                                <th width="10%">ID</th>
                                <th>Room</th>
                                <th width="10%"># Bids</th>
                                <th width="10%">Latest Bid</th>
                                <th width="20%">Time Remaining</th>
                                <th width="10%">Action</th>
                            </tr>
                            </thead>
                            @if($auctions->count() != 0)
                                <tbody>
                                @foreach($auctions as $row)
                                    <tr>
                                        <th scope="row">{{ $row->id }}</th>
                                        <td>{{ $row->room->name }}</td>
                                        <td>{{ $row->bids_count != 0 ? $row->bids_count : '-' }}</td>
                                        <td>{{ $row->latestBid != null ? $row->latestBid->display_price : '-' }}</td>
                                        <td>{{ $row->time_left }}</td>
                                        <td>
                                            <a class="btn btn-primary btn-xs"
                                               href="{{ route('auctions.show', ['id' => $row->id]) }}"
                                               role="button">Place Bid</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tbody>
                                <tr>
                                    <td colspan="3">No Auctions Found. Please check later.</td>
                                </tr>
                                </tbody>
                            @endif
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection