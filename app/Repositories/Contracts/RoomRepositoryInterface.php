<?php

namespace App\Repositories\Contracts;

interface RoomRepositoryInterface extends RepositoryInterface
{
    public function inactive();
}