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
  
      
    }
}
