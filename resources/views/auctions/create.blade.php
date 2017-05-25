@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Auction</div>

                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('auctions.store') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('room_id') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-2 control-label">Room</label>

                                <div class="col-md-6">

                                    <select id="room_id" class="form-control" name="room_id">
                                        <option value="">Select Room</option>
                                        @foreach($rooms as $room)
                                            @if(old('room_id') == $room->id)
                                                <option value="{{ $room->id }}" selected="selected">{{ $room->name }}</option>
                                            @else
                                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @if ($errors->has('room_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('room_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">
                                        Add Auction
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
