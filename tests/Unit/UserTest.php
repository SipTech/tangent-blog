<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic get all users test.
     */
    public function test_get_all_user_request(): void
    {
        $response = $this->get('/api/users');
        $response->assertStatus(200);
    }
}
