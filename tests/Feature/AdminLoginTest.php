<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminLoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginForm()
    {
        $response = $this->get('/panel/login');

        $response->assertSeeText('Sign In');

        $response->assertSee('Login');

        $response->assertStatus(200);
    }
}
