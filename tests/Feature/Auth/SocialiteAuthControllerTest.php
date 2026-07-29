<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class SocialiteAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Redirects to Google authentication URL.
     */
    public function test_redirects_to_google()
    {
        $response = $this->get(route('auth.google'));

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
    }

    /**
     * Test: Handles Google callback and registers a new user.
     */
    public function test_handles_google_callback_and_creates_new_user()
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-id-12345')
                     ->shouldReceive('getName')->andReturn('Google Tester')
                     ->shouldReceive('getEmail')->andReturn('tester@google.com');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'name'        => 'Google Tester',
            'email'       => 'tester@google.com',
            'social_id'   => 'google-id-12345',
            'social_type' => 'google',
            'role'        => 'user',
        ]);

        $this->assertAuthenticated();
    }

    /**
     * Test: Handles Google callback and logs in existing user.
     */
    public function test_handles_google_callback_and_logs_in_existing_user()
    {
        $user = User::factory()->create([
            'email'       => 'tester@google.com',
            'social_id'   => 'google-id-12345',
            'social_type' => 'google',
            'role'        => 'user',
        ]);

        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('google-id-12345')
                     ->shouldReceive('getName')->andReturn('Google Tester')
                     ->shouldReceive('getEmail')->andReturn('tester@google.com');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
