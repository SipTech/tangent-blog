<?php

namespace Tests\Feature;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserFactoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the User Factory
     *
     * @return void
     */
    public function testUserFactory()
    {
        $user = User::factory()->make([
            'name' => 'Test User',
            'email' => 'runte.heidi@crist.com',
            'password' => Hash::make('testPassword'),
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('runte.heidi@crist.com', $user->email);
        $this->assertTrue(Hash::check('testPassword', $user->password));
    }
}
