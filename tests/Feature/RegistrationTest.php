<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_guest_can_view_registration()
    {

        $this->get('/register')
            ->assertStatus(200)
            ->assertSee('Name')
            ->assertSee('E-Mail Address')
            ->assertSee('Password')
            ->assertSee('Callback URL');

    }

    /** @test */
    function a_logged_in_user_is_redirected_to_homepage()
    {

        $this->signInAsPartner();

        $this->get('/register')
            ->assertStatus(302)
            ->assertRedirect('/');

        $this->signInAsAdmin();

        $this->get('/register')
            ->assertStatus(302)
            ->assertRedirect('/');

    }

    /** @test */
    function a_guest_cannot_register_without_name()
    {
        $this->submitRegistration(['name' => null])
            ->assertSessionHasErrors('name');

    }

    /** @test */
    function a_guest_cannot_register_without_email()
    {
        $this->submitRegistration(['email' => null])
            ->assertSessionHasErrors('email');

    }

    /** @test */
    function a_guest_cannot_register_without_password()
    {

        $this->submitRegistration(['password' => null])
            ->assertSessionHasErrors('password');

    }

    /** @test */
    function a_guest_cannot_register_without_callback_url()
    {
        $this->submitRegistration(['callback_url' => null])
            ->assertSessionHasErrors('callback_url');

    }

    /** @test */
    function a_guest_cannot_register_with_same_email()
    {
        $user = factory(User::class)->make(['email' => 'test@test.com']);

        $data = $user->toArray();
        $data['password'] = $user->password;
        $data['password_confirmation'] = $user->password;

        $this->post('/register', $data);
        $this->post('/logout');

        $this->post('/register', $data)
            ->assertSessionHasErrors(['email' => 'The email has already been taken.']);

    }

    /** @test */
    function a_guest_can_register()
    {
        $role = $this->createRole(self::ROLE_PARTNER, 'partner');

        $user = factory(User::class)->make(['role_id' => $role->id]);

        $data = $user->toArray();
        $data['password'] = $user->password;
        $data['password_confirmation'] = $user->password;

        $response = $this->post('/register', $data)
            ->assertStatus(302);

        $this->get($response->headers->get('Location'))
            ->assertStatus(200)
            ->assertSee('Logout');
    }

    function submitRegistration($overrides = [])
    {

        $user = factory(User::class)->make($overrides);
        return $this->post('/register', $user->toArray());
    }
}
