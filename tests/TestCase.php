<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    const ROLE_ADMIN = 1;
    const ROLE_PARTNER = 2;

    /**
     * Signed in as an admin
     *
     * @return $this
     */
    protected function signInAsAdmin()
    {

        $user = $this->createAdminUser();

        $this->actingAs($user);

        return $this;

    }

    /**
     * Signed in as partner
     *
     * @return $this
     */
    protected function signInAsPartner()
    {

        $user = $this->createPartnerUser();

        $this->actingAs($user);

        return $this;

    }

    /**
     * Create admin user
     *
     * @param array $overrides
     * @return mixed
     */
    protected function createAdminUser($overrides = [])
    {

        $role = $this->createRole(self::ROLE_ADMIN, 'admin');

        $overrides = array_merge($overrides, ['role_id' => $role->id]);
        return factory(User::class)->create($overrides);

    }

    /**
     * Create partner user
     *
     * @param array $overrides
     * @return mixed
     */
    protected function createPartnerUser($overrides = [])
    {

        $role = $this->createRole(self::ROLE_PARTNER, 'partner');

        $overrides = array_merge($overrides, ['role_id' => $role->id]);
        return factory(User::class)->create($overrides);

    }

    /**
     * Create roles for user
     *
     * @return mixed
     */
    protected function createRole($id, $name)
    {

        return factory(Role::class)->create([
            'id' => $id,
            'name' => $name
        ]);
    }
}
