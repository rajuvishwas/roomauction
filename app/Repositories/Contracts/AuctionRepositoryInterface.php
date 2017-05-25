<?php

namespace App\Repositories\Contracts;

interface AuctionRepositoryInterface extends RepositoryInterface
{
    public function auctionable();
}