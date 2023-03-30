<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private function getUserWithHashedPassword(): array
    {
        $user = User::factory()->make([
            'name' => 'Test User',
            'email' => 'runte.heidi@crist.com',
            'password' => Hash::make('testPassword')
        ]);

        return [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'testPassword'
        ];
    }

    public function testUserIsRegisteredSuccessfully()
    {
        $response = $this->post('/api/auth/register', $this->getUserWithHashedPassword());
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'runte.heidi@crist.com'
        ]);
    }

    public function testUserIsAlreadyRegistered()
    {
        $this->post('/api/auth/register', $this->getUserWithHashedPassword());
        $response = $this->post('/api/auth/register', $this->getUserWithHashedPassword());
        $response->assertStatus(401);
    }

    public function testUserLogsInSuccessfully()
    {
        $user = User::factory()->create([
            'email' => 'runte.heidi@crist.com',
            'password' => Hash::make('testPassword')
        ]);

        $loginCreds = [
            'email' => 'runte.heidi@crist.com',
            'password' => 'testPassword'
        ];

        $response = $this->post('/api/auth/login', $loginCreds);
        $response->assertStatus(200);
    }
}
