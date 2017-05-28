@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Rooms
                        <div class="btn-group pull-right">
                            <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-xs">Add Room</a>
                        </div>
                    </div>

                    <div class="panel-body">

                        <table class="table">
                            <thead class="thead-inverse">
                            <tr>
                                <th width="20%">ID</th>
                                <th>Room</th>
                                <th width="10%">Min Bid</th>
                            </tr>
                            </thead>
                            @if($rooms->count() != 0)
                                <tbody>
                                @foreach($rooms as $row)
                                    <tr>
                                        <th scope="row">{{ $row->id }}</th>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->display_min_bid }}</td>
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

                        @if($rooms->count() != 0)
                        {{ $rooms->links() }}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
