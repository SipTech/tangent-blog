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

    private function getUserWithHashedPassword(): Array
    {
        $user = User::factory()->make(
            [
                'name'  => "Test User",
                'email' => "runte.heidi@crist.com",
                'password' => Hash::make('testPassword')
            ]
        );


    }

    public function testUserIsRegisteredSuccessfully()
    {
        $response = $this->post(
            '/api/auth/register', 
            $this->getUserWithHashedPassword()
        );
        //dd($this->getUserWithHashedPassword());
        $this->assertTrue($response['status']);
        $this->assertDatabaseHas('users', $this->getUserWithHashedPassword());
    }

    public function testUserIsAlreadyRegistered()
    {
        $response = $this->post('/api/auth/register', $this->getUserWithHashedPassword());
        $this->assertFalse($response['status']);
    }

    public function testUserLogsInSuccessfully()
    {
        $user = $this->getUserWithHashedPassword();
        $loginCreds = [
            'email' => $user['email'],
            'password' => $user['password']
        ];
        $response = $this->post('/api/auth/login', $user);
        $this->assertTrue($response['status']);
    }
}
