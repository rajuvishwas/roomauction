<?php

namespace Tests\Integration\Repositories\Eloquent;

use App\Models\Auction;
use App\Models\Bid;
use App\Repositories\Eloquent\AuctionRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuctionRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    protected $repository;

    protected $table;

    function setUp()
    {
        parent::setUp();

        $auction = new Auction();
        $this->table = $auction->getTable();

        $this->repository = new AuctionRepository($auction);
    }

    /** @test */
    function it_create_an_auction()
    {

        $auction = factory(Auction::class)->make();

        $this->repository->create($auction->toArray());

        $this->assertDatabaseHas($this->table, [
            'room_id' => $auction['room_id'],
            'expires_at' => $auction['expires_at']
        ]);

    }

    /** @test */
    function it_gets_auctionable_rooms()
    {
        factory(Auction::class, 3)->create();

        $auctions = $this->repository->auctionable();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $auctions);
        $this->assertCount(3, $auctions);
        $this->assertNull($auctions[0]->latestBid);

    }

    /** @test */
    function it_gets_auctionable_rooms_with_bids()
    {
        factory(Bid::class, 3)->create();

        $auctions = $this->repository->auctionable();

        $this->assertNotNull($auctions[0]->latestBid);
        $this->assertInstanceOf('App\Models\Bid', $auctions[0]->latestBid);
    }

    /** @test */
    function it_finds_auction()
    {
        $insertedAuction = factory(Auction::class)->create();

        $auction = $this->repository->find($insertedAuction->id);

        $this->assertInstanceOf('App\Models\Auction', $auction);
        $this->assertEquals($insertedAuction->id, $auction->id);
        $this->assertNull($auction->latestBid);
    }

    function it_finds_auction_with_bids()
    {
        $bid = factory(Bid::class)->create();

        $auction = $this->repository->find($bid->auction_id);

        $this->assertNotNull($auction->latestBid);
        $this->assertInstanceOf('App\Models\Bid', $auction->latestBid);

    }
}
