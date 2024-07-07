<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase;

class auth_spec_Test extends TestCase
{
    use RefreshDatabase;
  
    
    public function testShouldRegisterUserSuccessfully()
    {
        $response = $this->postJson('/api/auth/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johnkandol@gmail.com',
            'password' => 'password123',
            'phone' => '1234567890',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'accessToken',
                'user' => [
                    'userId',
                    'firstName',
                    'lastName',
                    'email',
                    'phone',
                ],
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johnkandol@gmail.com',
        ]);

        $this->assertDatabaseHas('organisations', [
            'name' => "John's Organisation",
        ]);
    }

    public function testShouldFailIfRequiredFieldsAreMissing()
    {
        $response = $this->postJson('/api/auth/register', [
            'firstName' => 'John',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => [
                ['field', 'message'],
            ],
        ]);
    }

    public function testShouldFailIfDuplicateEmail()
    {
        User::create([
            'userId' => Str::uuid(),
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johnkandol@gmail.com',
            'password' => Hash::make('password123'),
            'phone' => '1234567890',
        ]);

        $response = $this->postJson('/api/auth/register', [
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'email' => 'johnkandol@gmail.com',
            'password' => 'password123',
            'phone' => '0987654321',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => [
                ['field', 'message'],
            ],
        ]);
    }
    public function testShouldLoginUserSuccessfully()
    {
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password123'),
        ]);
    
        $response = $this->postJson('/api/auth/login', [
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);
    
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'accessToken',
                'user' => [
                    'userId',
                    'firstName',
                    'lastName',
                    'email',
                    'phone',
                ],
            ],
        ]);
        $this->assertArrayHasKey('data', $response->json());
    }
    public function testShouldFailIfRequiredFieldsAreMissingDuringLogin()
    {

        $response = $this->postJson('/api/auth/login', [
            'password' => 'password123',
        ]);
    
        $response->assertStatus(422)
                 ->assertJson([
                     'message' => 'The email field is required.',
                     'errors' => [
                         'email' => [
                             'The email field is required.'
                         ]
                     ]
                 ]);
    }
}
