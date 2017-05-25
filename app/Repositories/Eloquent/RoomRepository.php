<?php

namespace App\Repositories\Eloquent;

use App\Models\Room;
use App\Repositories\Contracts\RoomRepositoryInterface;
use Carbon\Carbon;

class RoomRepository extends Repository implements RoomRepositoryInterface
{
    /**
     * @var Room
     */
    protected $model;

    public function __construct(Room $model)
    {

        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function inactive()
    {
        return $this->model->whereDoesntHave('auctions', function ($query) {
            $query->where('expires_at', '=', NULL)
                ->orWhere('expires_at', '>=', Carbon::now());
        })->get();
    }
}