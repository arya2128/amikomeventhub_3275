<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Login page loads successfully.
     */
    public function test_login_page_loads_successfully()
    {
        $response = $this->get(route('admin.login'));
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test: Admin can login with correct credentials.
     */
    public function test_admin_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'role'     => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email'    => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test: Admin cannot login with incorrect credentials.
     */
    public function test_admin_cannot_login_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'role'     => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email'    => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    /**
     * Test: Logged in admin can logout.
     */
    public function test_logged_in_admin_can_logout()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post(route('admin.logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
