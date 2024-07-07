<?php

namespace Tests\Unit;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class tokenTest extends TestCase
{
    use RefreshDatabase;
    public function testTokenGeneration()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        $this->assertNotNull($token);
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->userId,
            'tokenable_type' => get_class($user),
        ]);
    }
  
   
}
