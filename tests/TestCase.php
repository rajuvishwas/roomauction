<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    const ROLE_ADMIN = 1;
    const ROLE_PARTNER = 2;
}
