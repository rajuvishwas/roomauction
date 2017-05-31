<?php

namespace Tests\Feature;

use App\Models\Room;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoomTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_guest_cannot_view_or_create_rooms()
    {
        $this->get('/rooms')
            ->assertRedirect('/login');

        $this->post('/rooms')
            ->assertRedirect('/login');
    }

    /** @test */
    function a_partner_cannot_view_or_create_rooms()
    {

        $this->signInAsPartner();

        $this->get('/rooms')
            ->assertRedirect('/')
            ->assertSessionHas('error', 'Unauthorized Access');

        $this->post('/rooms')
            ->assertRedirect('/')
            ->assertSessionHas('error', 'Unauthorized Access');

    }

    /** @test */
    function an_admin_can_view_rooms()
    {
        $this->signInAsAdmin();

        $this->get('/rooms')
            ->assertStatus(200)
            ->assertSee('Add Room');
    }

    /** @test */
    function an_admin_can_create_a_room()
    {

        $this->signInAsAdmin();

        $room = factory(Room::class)->make();

        $response = $this->post('/rooms', $room->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($room->name)
            ->assertSee('Room has been added.');

    }

    /** @test */
    function a_room_requires_a_name()
    {
        $this->submitRoom(['name' => null])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    function a_room_requires_a_min_bid()
    {
        $this->submitRoom(['min_bid' => null])
            ->assertSessionHasErrors('min_bid');
    }

    /** @test */
    function a_room_min_bid_has_to_be_integer()
    {
        $this->submitRoom(['min_bid' => 'test'])
            ->assertSessionHasErrors('min_bid');
    }

    function submitRoom($overrides = [])
    {
        $this->signInAsAdmin();

        $room = factory(Room::class)->make($overrides);

        return $this->post('/rooms', $room->toArray());
    }
}
