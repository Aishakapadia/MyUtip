<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDashboardTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAdminDashboard()
    {
        // Given we have authenticated user
        $this->be($user = factory('App\User')->create());

        $response = $this->get('/panel/dashboard');

        $response->assertSeeText('Admin Dashboard');

        $response->assertSee('comments');

        $this->assertTrue(true);
    }
}
