<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{
    public function testUserIsRegisteredSuccessfully()
    {
        $payload = [
            'name'  => $this->faker->name,
            'email'      => $this->faker->email,
            'password' => 'testPassword'
        ];
        $this->json('post', 'auth/register', $payload)
         ->assertStatus(Response::HTTP_CREATED)
         ->assertJsonStructure(
             [
                 'data' => [
                     'id',
                     'first_name',
                     'email',
                     'created_at'
                 ]
             ]
         );
        $this->assertDatabaseHas('users', $payload);
    }
}
