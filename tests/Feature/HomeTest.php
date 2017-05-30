<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_guest_can_view_homepage()
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Login')
            ->assertSee('Register');
    }

    /** @test */
    function a_partner_can_view_dashboard()
    {
        $this->signInAsPartner();

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Auctions')
            ->assertSee('Dashboard')
            ->assertSee('Logout');
    }

    /** @test */
    function an_admin_can_view_dashboard()
    {
        $this->signInAsAdmin();

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Rooms')
            ->assertSee('Auctions')
            ->assertSee('Dashboard')
            ->assertSee('Logout');
    }
}
