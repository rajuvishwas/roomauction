<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_guest_can_view_registration() {

        $this->get('/register')
            ->assertStatus(200)
            ->assertSee('Name')
            ->assertSee('E-Mail Address')
            ->assertSee('Password')
            ->assertSee('Callback URL');

    }

    /** @test */
    function a_logged_in_user_is_redirected_to_homepage() {

        $this->signInAsPartner();

        $this->get('/register')
            ->assertStatus(302)
            ->assertRedirect('/');

        $this->signInAsAdmin();

        $this->get('/register')
            ->assertStatus(302)
            ->assertRedirect('/');

    }

    function a_guest_cannot_register_without_email() {



    }
}
