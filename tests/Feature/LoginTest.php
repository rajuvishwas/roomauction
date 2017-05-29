<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_can_view_login_form()
    {
        // When guest visits login page
        $response = $this->get('/login');

        // then display login form
        $response->assertStatus(200);
        $response->assertSee('E-Mail Address');
        $response->assertSee('Password');
    }

    /** @test */
    public function a_logged_in_user_is_redirected_to_homepage()
    {
        // Given that we have a user
        $user = factory(User::class)->create([
            'role_id' => self::ROLE_PARTNER
        ]);

        // and we are logged in
        $this->actingAs($user);

        // when user visit login page
        $response = $this->get('/login');

        // then redirect to homepage
        $response->assertStatus(302);
        $response->assertRedirect('/');

    }

    /** @test */
    function a_user_can_logout()
    {

        // Given that we have a logged in user
        $user = factory(User::class)->create([
            'role_id' => self::ROLE_PARTNER
        ]);

        $this->actingAs($user);

        // When click on logout
        $response = $this->post('/logout');

        // then redirect to homepage
        $response->assertStatus(302);
        $response->assertRedirect('/');

    }

    /** @test */
    function a_guest_cannot_login_without_email()
    {

        $response = $this->post('/login');
        $response->assertSessionHasErrors(['email' => 'The email field is required.']);
    }

    /** @test */
    function a_guest_cannot_login_without_password()
    {

        $response = $this->post('/login');
        $response->assertSessionHasErrors(['password' => 'The password field is required.']);
    }
}
