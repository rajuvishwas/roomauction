<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_can_view_login_form()
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('E-Mail Address')
            ->assertSee('Password');
    }

    /** @test */
    public function a_logged_in_user_is_redirected_to_homepage()
    {
        $this->signInAsPartner();

        $this->get('/login')
            ->assertStatus(302)
            ->assertRedirect('/');

        $this->signInAsAdmin();

        $this->get('/login')
            ->assertStatus(302)
            ->assertRedirect('/');

    }

    /** @test */
    function a_user_can_logout()
    {

        $this->signInAsPartner();

        $this->post('/logout')
            ->assertStatus(302)
            ->assertRedirect('/');

        $this->signInAsAdmin();

        $this->post('/logout')
            ->assertStatus(302)
            ->assertRedirect('/');

    }

    /** @test */
    function a_guest_cannot_login_without_email()
    {

        $this->post('/login')
            ->assertStatus(302)
            ->assertSessionHasErrors(['email' => 'The email field is required.']);
    }

    /** @test */
    function a_guest_cannot_login_without_password()
    {

        $this->post('/login')
            ->assertStatus(302)
            ->assertSessionHasErrors(['password' => 'The password field is required.']);
    }

    /** @test */
    function a_guest_cannot_login_with_invalid_password()
    {
        $user = $this->createPartnerUser([
            'password' => bcrypt('test')
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'testing'])
            ->assertStatus(302)
            ->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);

    }

    /** @test */
    function a_user_can_logged_in()
    {
        $user = $this->createPartnerUser([
            'password' => bcrypt('test')
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'test'])
            ->assertStatus(302)
            ->assertSessionMissing('errors');

        $this->get('/')
            ->assertSee('Dashboard');
    }
}
