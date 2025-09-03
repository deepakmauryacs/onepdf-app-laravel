<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials(): void
    {
        $password = 'secret123';
        $user = User::factory()->create([
            'password' => $password,
        ]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertAuthenticatedAs($user);
    }
}
