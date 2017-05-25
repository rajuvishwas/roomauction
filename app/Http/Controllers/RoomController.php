<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Repositories\Contracts\RoomRepositoryInterface as Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * @var Room
     */
    private $repository;

    /**
     * RoomController constructor.
     * @param Room $repository
     */
    public function __construct(Room $repository)
    {
        $this->middleware('auth.admin');
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = $this->repository->paginate(env('APP_RESULTS_PER_PAGE'));
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoomRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomRequest $request)
    {
        $this->repository->create($request->all());

        return redirect('rooms')
            ->with('status', 'Room has been added');
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
     * @param RoomRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoomRequest $request, $id)
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
