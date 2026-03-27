<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_register_page_can_be_accessed()
    {
        $response = $this->get('/register'); 

        $response->assertStatus(200);
    }
}