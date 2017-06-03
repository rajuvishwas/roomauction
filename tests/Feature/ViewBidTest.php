<?php

namespace Tests\Feature;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewBidTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    function a_guest_cannot_view_bid()
    {
        $this->get('/auctions/1/1')
            ->assertRedirect('/login');
    }

    /** @test */
    function a_partner_can_view_bid()
    {
        $user = $this->createPartnerUser();
        $this->actingAs($user);

        $bid = factory(Bid::class)->create(['user_id' => $user->id]);

        $this->get('/auctions/' . $bid->auction->id . '/' . $bid->encoded_key)
            ->assertSee($bid->auction->room->name)
            ->assertSee($bid->price);
    }

    /** @test */
    function a_partner_cannot_view_other_partners_bid()
    {

        $bid = factory(Bid::class)->create();

        $user = $this->createPartnerUser();
        $this->actingAs($user);

        $this->get('/auctions/' . $bid->auction->id . '/' . $bid->encoded_key)
            ->assertRedirect('/')
            ->assertSessionHas(['error' => 'Unauthorized Access']);

    }

    /** @test */
    function a_partner_can_view_if_their_bid_is_winner() {

        $user = $this->createPartnerUser();
        $this->actingAs($user);

        $auction = factory(Auction::class)->create([
            'expires_at' => Carbon::now()
        ]);

        $bid = factory(Bid::class)->create([
            'user_id' => $user->id,
            'auction_id' => $auction->id
        ]);

        $this->get('/auctions/' . $bid->auction->id . '/' . $bid->encoded_key)
            ->assertSee('Expired')
            ->assertSee('Your bid is the winner.');
    }

    /** @test */
    function a_partner_can_view_if_their_bid_is_not_the_winner() {

        $auction = factory(Auction::class)->create();

        $firstUser = $this->createPartnerUser();
        $this->actingAs($firstUser);

        $secondUser = factory(User::class)->create([
            'role_id' => $firstUser->role_id
        ]);

        $firstBid = factory(Bid::class)->create([
            'user_id' => $firstUser->id,
            'auction_id' => $auction->id
        ]);

        factory(Bid::class)->create([
            'user_id' => $secondUser->id,
            'auction_id' => $auction->id,
            'price' => $this->getAcceptedBidPrice($firstBid->price)
        ]);

        $this->get('/auctions/' . $firstBid->auction->id . '/' . $firstBid->encoded_key)
            ->assertSee('Your bid is not a winner.');

    }
}
