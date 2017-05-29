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
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertSee('Register');
        $response->assertDontSee('Logout');
    }

    /** @test */
    function a_partner_can_view_dashboard()
    {
        // Given that we have user with partners role
        $role = factory(Role::class)->create([
            'id' => self::ROLE_PARTNER,
            'name' => 'partner'
        ]);

        $user = factory(User::class)->create([
            'role_id' => $role->id
        ]);

        // and we are logged in as partner
        $this->actingAs($user);

        // When we visit homepage
        $response = $this->get('/');

        // Then we view dashboard with auction menu
        $response->assertStatus(200);
        $response->assertSee('Auctions');
        $response->assertSee('Dashboard');
        $response->assertSee('Logout');
        $response->assertDontSee('Login');
        $response->assertDontSee('Register');
    }

    /** @test */
    function an_admin_can_view_dashboard()
    {
        // Given that we have user with partners role
        $role = factory(Role::class)->create([
            'id' => self::ROLE_ADMIN,
            'name' => 'admin'
        ]);

        $user = factory(User::class)->create([
            'role_id' => $role->id
        ]);

        // and we are logged in as partner
        $this->actingAs($user);

        // When we visit homepage
        $response = $this->get('/');

        // Then we view dashboard with rooms and auction menu
        $response->assertStatus(200);
        $response->assertSee('Rooms');
        $response->assertSee('Auctions');
        $response->assertSee('Dashboard');
        $response->assertSee('Logout');
        $response->assertDontSee('Login');
        $response->assertDontSee('Register');
    }
}
