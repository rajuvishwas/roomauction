<?php

namespace App\Repositories\Contracts;

interface BidRepositoryInterface extends RepositoryInterface
{
    public function findAllByAuction($auctionId);
}