<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_dashboard_page_can_be_accessed()
    {
     $response = $this->get('/dashboard');

       $response->assertRedirect('/login');
    }
}