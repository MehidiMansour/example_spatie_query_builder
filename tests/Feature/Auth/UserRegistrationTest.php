<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected array $payload;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function canRegisterAUserWithValidDetails()
    {
        $this->postJson('/api/register', $this->payload)
        ->assertStatus(200);
    }
    /** @test */
    public function canNotCreateUserWithAnExistingEmail()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $payload = array_merge($this->payload, ['email' => $user->email]);
        $this->postJson('/api/register', $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
    }
    protected function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'name' => 'test',
            'email' => 'test@email.com',
            'password' => 'password',
        ];
    }
}