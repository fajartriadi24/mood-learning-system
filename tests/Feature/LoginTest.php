<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login_page_can_be_accessed()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}