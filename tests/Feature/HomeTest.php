<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHomepage()
    {
        //$this->assertTrue(true);
        $response = $this->get('/');

        $response->assertSeeText('Laravel');

        $response->assertStatus(200);
    }
}
