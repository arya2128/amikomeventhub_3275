<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /**
     * Test: Check-in page is protected by admin middleware.
     */
    public function test_checkin_page_requires_admin_auth()
    {
        $response = $this->get(route('admin.checkin'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Check-in page loads successfully for logged-in admin.
     */
    public function test_checkin_page_loads_for_admin()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.checkin'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.checkin');
    }

    /**
     * Test: Verify a valid, unused ticket.
     */
    public function test_verify_valid_unused_ticket()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transaction = Transaction::create([
            'event_id'       => $event->id,
            'order_id'       => 'TRX-TEST-12345',
            'customer_name'  => 'Test User',
            'customer_email' => 'test@example.com',
            'customer_phone' => '08123',
            'total_price'    => 10000,
            'status'         => 'success', // Lunas
        ]);

        $response = $this->actingAs($this->admin)
                         ->post(route('admin.checkin.verify'), ['order_id' => 'TRX-TEST-12345']);

        $response->assertStatus(200);
        $response->assertJson([
            'status'  => 'success',
            'message' => 'Check-in berhasil! Selamat datang, Test User.',
        ]);

        // Status di database berubah menjadi 'used'
        $this->assertEquals('used', $transaction->fresh()->status);
    }

    /**
     * Test: Double entry prevention (already used ticket).
     */
    public function test_verify_already_used_ticket()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transaction = Transaction::create([
            'event_id'       => $event->id,
            'order_id'       => 'TRX-TEST-54321',
            'customer_name'  => 'Test User',
            'customer_email' => 'test@example.com',
            'customer_phone' => '08123',
            'total_price'    => 10000,
            'status'         => 'used', // Sudah check-in sebelumnya
        ]);

        $response = $this->actingAs($this->admin)
                         ->post(route('admin.checkin.verify'), ['order_id' => 'TRX-TEST-54321']);

        $response->assertStatus(400);
        $response->assertJson([
            'status'  => 'error',
            'message' => 'Tiket sudah pernah digunakan sebelumnya!',
        ]);
    }

    /**
     * Test: Verify ticket with invalid/non-existent order ID.
     */
    public function test_verify_invalid_ticket()
    {
        $response = $this->actingAs($this->admin)
                         ->post(route('admin.checkin.verify'), ['order_id' => 'TRX-INVALID-999']);

        $response->assertStatus(404);
        $response->assertJson([
            'status'  => 'error',
            'message' => 'Tiket tidak ditemukan!',
        ]);
    }
}
