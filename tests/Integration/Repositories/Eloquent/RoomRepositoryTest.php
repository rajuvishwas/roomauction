<?php

namespace Tests\Integration\Repositories\Eloquent;

use App\Models\Auction;
use App\Models\Room;
use App\Repositories\Eloquent\RoomRepository;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoomRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    protected $repository;

    protected $table;

    function setUp()
    {
        parent::setUp();

        $room = new Room();
        $this->table = $room->getTable();

        $this->repository = new RoomRepository($room);
    }

    /** @test */
    function it_gets_all_inactive_rooms()
    {
        // Creating 3 rooms
        factory(Room::class, 3)->create();

        // Creating 1 room with expired auction
        factory(Auction::class)->create(['expires_at' => Carbon::now()->subMinute(1)]);

        $rooms = $this->repository->inactive();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $rooms);
        $this->assertCount(4, $rooms);
    }
}
