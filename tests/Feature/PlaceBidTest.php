<?php

namespace Tests\Feature;

use App\Models\Auction;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlaceBidTest extends TestCase
{
    use DatabaseMigrations;

    protected $auction;

    function setUp()
    {
        parent::setUp();

        $this->auction = factory(Auction::class)->create();
    }

    /** @test */
    function a_guest_cannot_bid()
    {
        $this->post('/bids')
            ->assertRedirect('/login');
    }
}
