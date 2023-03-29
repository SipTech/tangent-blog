<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserControllerTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic get all users test.
     */
    public function test_get_all_user_request(): void
    {
        $user = User::factory->make();
        $response = $this->actingAs($user)->get('/api/users');
        $response->assertStatus(200);
    }

    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/users')
         ->assertStatus(Response::HTTP_OK)
         ->assertJsonStructure(
             [
                 'data' => [
                     '*' => [
                         'id',
                         'name',
                         'email',
                         'created_at'
                     ]
                 ]
             ]
         );
    }

    public function testUserIsShownCorrectly()
    {
        $user = User::create(
            [
                'name'  => $this->faker->name,
                'email' => $this->faker->email,
                'created_at' => now()
            ]
        );

        $this->json('get', "api/user/$user->id")
         ->assertStatus(Response::HTTP_OK)
         ->assertExactJson(
             [
                 'data' => [
                     'id' => $user->id,
                     'name'  => $user->name,
                     'email' => $user->email,
                     'created_at' => (string)$user->created_at
                 ]
             ]
         );
    }

}
