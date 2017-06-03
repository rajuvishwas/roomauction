<?php

namespace Tests\Feature;

use App\Jobs\SendOutbidNotification;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlaceBidTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_guest_cannot_bid()
    {
        $this->post('/bids')
            ->assertRedirect('/login');
    }

    /** @test */
    function a_partner_can_bid()
    {

        $this->signInAsPartner();

        $bid = factory(Bid::class)->make();

        $response = $this->post('/bids', $bid->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($bid->auction->room->name)
            ->assertSee('Your bid has been placed.')
            ->assertSee('Your bid is winning.')
            ->assertSee($bid->price);

    }

    /** @test */
    function a_partner_cannot_bid_on_expired_auction()
    {

        $this->signInAsPartner();

        $auction = factory(Auction::class)->create([
            'expires_at' => Carbon::now()
        ]);

        $bid = factory(Bid::class)->make(['auction_id' => $auction->id]);

        $this->post('/bids', $bid->toArray())
            ->assertStatus(302)
            ->assertRedirect('/auctions')
            ->assertSessionHas('info', 'Auction has expired. Please bid on another auction.');
    }

    /** @test */
    function a_bid_requires_a_price()
    {
        $this->signInAsPartner();

        $bid = factory(Bid::class)->make();
        $bid->price = null;

        $this->post('/bids', $bid->toArray())
            ->assertSessionHasErrors('price');
    }

    /** @test */
    function a_partner_cannot_bid_lower_than_min_bid()
    {
        $this->signInAsPartner();

        $bid = factory(Bid::class)->make();
        $bid->price = $bid->price - 1;

        $this->post('/bids', $bid->toArray())
            ->assertSessionHasErrors('price');

    }

    /** @test */
    function a_partner_cannot_bid_lower_than_latest_bid()
    {
        $this->signInAsPartner();

        $firstBid = factory(Bid::class)->make();
        $this->post('/bids', $firstBid->toArray());

        $secondBid = factory(Bid::class)->make(['auction_id' => $firstBid->auction_id]);
        $secondBid->price = $firstBid->price;

        $this->post('/bids', $secondBid->toArray())
            ->assertSessionHasErrors('price');
    }

    /** @test */
    function a_partner_cannot_bid_lower_than_accepted_percentage_than_last_bid()
    {
        $this->signInAsPartner();

        $firstBid = factory(Bid::class)->make();
        $this->post('/bids', $firstBid->toArray());

        $secondBid = factory(Bid::class)->make(['auction_id' => $firstBid->auction_id]);
        $secondBid->price = $this->getAcceptedBidPrice($firstBid->price) - 1;

        $this->post('/bids', $secondBid->toArray())
            ->assertSessionHas('error')
            ->assertRedirect('/auctions/' . $firstBid->auction_id);
    }

    /** @test */
    function an_auction_is_extended_when_last_minute_bid_is_placed()
    {
        $user = $this->createPartnerUser();
        $this->actingAs($user);

        $auction = factory(Auction::class)->create([
            'expires_at' => Carbon::now()->addMinute(1)
        ]);

        $bid = factory(Bid::class)->make([
            'user_id' => $user->id,
            'auction_id' => $auction->id
        ]);

        $this->post('/bids', $bid->toArray());

        $this->assertNotEquals(
            $auction->expires_at,
            $bid->auction->expires_at
        );

        $this->assertEquals(
            config('app.bid_lastminute_extend'),
            $bid->auction->expires_at->diffInMinutes($auction->expires_at)
        );

    }

    /** @test */
    function a_notification_is_sent_to_previous_highest_bidder()
    {

        $auction = factory(Auction::class)->create();

        // First Bid
        $firstPartner = $this->createPartnerUser(['callback_url' => null]);
        $this->actingAs($firstPartner);

        $firstBid = factory(Bid::class)->create([
            'user_id' => $firstPartner->id,
            'auction_id' => $auction->id
        ]);

        // Second Bid
        $secondPartner = factory(User::class)->create(['role_id' => $firstPartner->role_id]);
        $this->actingAs($secondPartner);

        $secondBid = factory(Bid::class)->make([
            'user_id' => $secondPartner->id,
            'auction_id' => $auction->id,
            'price' => $this->getAcceptedBidPrice($firstBid->price)
        ]);

        Queue::fake();

        $this->post('/bids', $secondBid->toArray());

        Queue::assertPushed(SendOutbidNotification::class, function ($job) use ($firstBid, $secondBid) {
            return $job->getOldBid()->user_id == $firstBid->user_id
                && $job->getNewBid()->user_id == $secondBid->user_id;
        });

    }
}
