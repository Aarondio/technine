<?php

namespace Tests\Unit;


use App\Models\Organisation;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class organisationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_cannot_access_data_from_organizations_they_are_not_associated_with()
    {


        $organizationA = Organisation::factory()->create(['name' => "Org A"]);
        $organizationB = Organisation::factory()->create(['name' => "Org B"]);

        $userA = User::factory()->create();
        $userA->organisations()->attach($organizationA->id);

        $userB = User::factory()->create();

        $responseA = $this->actingAs($userA)->get('/api/organizations/');
        $responseB = $this->actingAs($userA)->get('/api/organizations/');

        $responseA->assertStatus(200);
        $responseB->assertStatus(403);
    }
}
