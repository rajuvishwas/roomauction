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
                                <th width="20%">Time Remaining</th>
                                <th width="20%">Action</th>
                            </tr>
                            </thead>
                            @if($auctions->count() != 0)
                                <tbody>
                                @foreach($auctions as $row)
                                    <tr>
                                        <th scope="row">{{ $row->id }}</th>
                                        <td>{{ $row->room->name }}</td>
                                        <td>{{ $row->displayTimeLeft() }}</td>
                                        <td>
                                            <a class="btn btn-info btn-xs" href="#" role="button">Bid</a>
                                            @if(Auth::user()->isAdmin())
                                                <a class="btn btn-danger btn-xs" href="#" role="button">Delete</a>
                                            @endif
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

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection