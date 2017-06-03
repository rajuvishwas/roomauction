<?php

namespace Tests\Integration\Repositories\Eloquent;

use App\Models\Auction;
use App\Models\Bid;
use App\Repositories\Eloquent\BidRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BidRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    protected $repository;

    protected $table;

    function setUp()
    {
        parent::setUp();

        $bid = new Bid();
        $this->table = $bid->getTable();

        $this->repository = new BidRepository($bid);
    }

    /** @test */
    function it_gets_bids_for_auction()
    {
        $auction = factory(Auction::class)->create();
        factory(Bid::class, 20)->create(['auction_id' => $auction->id]);

        $bids = $this->repository->findAllByAuction($auction->id);

        $this->assertInstanceOf('App\Pagination\CursorPaginator', $bids);
        $this->assertCount(intval(config('app.results_per_page')), $bids);

    }
}
