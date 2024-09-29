<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPreferencesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_update_thresholds()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put('/user/profile-information', [
                'name'                      => $user->name,
                'email'                     => $user->email,
                'precipitation_threshold'   => 5.0,
                'uv_index_threshold'        => 8.0,
            ]);

        $this->assertDatabaseHas('users', [
            'id'                        => $user->id,
            'precipitation_threshold'   => 5.0,
            'uv_index_threshold'        => 8.0,
        ]);
    }

    /** @test */
    public function user_can_manage_cities()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/profile/manage-cities', [
                'newCity' => 'London',
            ]);

        $this->assertDatabaseHas('city_user', [
            'user_id' => $user->id,
            'city'    => 'London',
        ]);
    }
}
