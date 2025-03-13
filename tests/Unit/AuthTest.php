<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase; // Ensures fresh database for each test

    #[Test]
    public function it_can_create_a_user()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    #[Test]
    public function it_can_hash_password_correctly()
    {
        $password = 'securepassword';
        $hashedPassword = Hash::make($password);

        $this->assertNotEquals($password, $hashedPassword);
        $this->assertTrue(Hash::check($password, $hashedPassword));
    }

    #[Test]
    public function it_can_generate_authentication_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $this->assertNotEmpty($token);
    }

    #[Test]
    public function it_can_update_user_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword'),
        ]);

        $user->update(['password' => bcrypt('newpassword123')]);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }
}