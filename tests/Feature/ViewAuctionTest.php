<?php

namespace Tests\Feature;

use App\Models\Auction;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewAuctionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_guest_cannot_view_auctions()
    {
        $this->get('/auctions')
            ->assertRedirect('/login');
    }

    /** @test */
    function a_partner_can_view_auctions()
    {
        $this->signInAsPartner();

        $this->get('/auctions')
            ->assertStatus(200);
    }

    /** @test */
    function a_partner_cannot_view_add_auction_button()
    {

        $this->signInAsPartner();

        $this->get('/auctions')
            ->assertDontSee('Add Auction');

    }

    /** @test */
    function an_admin_can_view_add_auction_button()
    {

        $this->signInAsAdmin();

        $this->get('/auctions')
            ->assertSee('Add Auction');

    }

    /** @test */
    function a_partner_can_view_auctionable_room()
    {

        $this->signInAsPartner();

        $auctions = factory(Auction::class, 20)->create();

        $response = $this->getJson('/auctions')->json();

        $this->assertCount($auctions->count(), $response);

    }

    /** @test */
    function a_partner_can_view_auction_details()
    {
        $this->signInAsPartner();

        $auction = factory(Auction::class)->create();

        $this->get('/auctions/' . $auction->id)
            ->assertSee('Auction Details')
            ->assertSee($auction->room->name)
            ->assertSee($auction->room->min_bid);
    }

    /** @test */
    function a_partner_cannot_view_expired_auction()
    {
        $this->signInAsPartner();

        $auction = factory(Auction::class)->create(['expires_at' => Carbon::now()]);

        $this->get('/auctions/' . $auction->id)
            ->assertStatus(302)
            ->assertRedirect('/auctions');
    }
}
