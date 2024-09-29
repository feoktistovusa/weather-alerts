<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_subscription_form()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Subscribe to Weather Alerts');
    }

    /** @test */
    public function user_can_subscribe_with_valid_data()
    {
        $response = $this->post('/subscribe', [
            'email' => 'user@example.com',
            'city'  => 'London',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('subscribers', [
            'email' => 'user@example.com',
            'city'  => 'London',
        ]);
    }

    /** @test */
    public function subscription_requires_valid_email()
    {
        $response = $this->post('/subscribe', [
            'email' => 'invalid-email',
            'city'  => 'London',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
