<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function canNotLoginWithWrongCredentials()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->assertTrue($user->hasVerifiedEmail());

        $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'wrong_password',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
    }
    /** @test */
    public function canLoginWithCorrectCredentials()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->assertTrue($user->hasVerifiedEmail());

        /** @noinspection SpellCheckingInspection */
        $this->postJson('api/login', [
            'email' => $user->email,
            'password' => '123456',
        ])->assertStatus(200);
    }
    
}