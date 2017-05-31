<?php

namespace Tests\Feature;

use App\Models\Auction;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateAuctionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_guest_cannot_create_room()
    {

        $this->post('/auctions')
            ->assertRedirect('/login');

    }

    /** @test */
    function a_parnter_cannot_create_room()
    {

        $this->signInAsPartner();

        $this->post('/auctions')
            ->assertRedirect('/')
            ->assertSessionHas('error', 'Unauthorized Access');

    }

    /** @test */
    function an_admin_can_create_an_auction()
    {
        $this->signInAsAdmin();

        $auction = factory(Auction::class)->make();

        $response = $this->post('/auctions', $auction->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($auction->room->name)
            ->assertSee('Auction has been added.');

    }

    /** @test */
    function an_auction_requires_a_room()
    {

        $this->signInAsAdmin();

        $this->post('/auctions', ['room_id' => null])
            ->assertSessionHasErrors('room_id');

    }

    /** @test */
    function an_auction_requires_a_valid_room()
    {

        $this->signInAsAdmin();

        $this->post('/auctions', ['room_id' => 1])
            ->assertSessionHasErrors('room_id');
    }
}
