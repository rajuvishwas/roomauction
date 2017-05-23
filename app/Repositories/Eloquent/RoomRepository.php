<?php

namespace App\Repositories\Eloquent;

use App\Models\Room;
use App\Repositories\Contracts\RoomRepositoryInterface;

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
}