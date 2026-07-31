<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenantTest extends TestCase
{
    use RefreshDatabase;

    protected $superadmin;
    protected $organizerA;
    protected $organizerB;
    protected $eventA;
    protected $eventB;
    protected $transactionA;
    protected $transactionB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superadmin = User::factory()->create(['role' => 'admin']);
        $this->organizerA = User::factory()->create(['role' => 'organizer']);
        $this->organizerB = User::factory()->create(['role' => 'organizer']);

        $category = Category::factory()->create();

        $this->eventA = Event::factory()->create([
            'category_id' => $category->id,
            'user_id'     => $this->organizerA->id,
            'title'       => 'Event Organizer A',
        ]);

        $this->eventB = Event::factory()->create([
            'category_id' => $category->id,
            'user_id'     => $this->organizerB->id,
            'title'       => 'Event Organizer B',
        ]);

        $this->transactionA = Transaction::create([
            'event_id'       => $this->eventA->id,
            'order_id'       => 'TRX-ORG-A',
            'customer_name'  => 'Customer A',
            'customer_email' => 'a@example.com',
            'customer_phone' => '08123',
            'total_price'    => 15000,
            'status'         => 'success',
        ]);

        $this->transactionB = Transaction::create([
            'event_id'       => $this->eventB->id,
            'order_id'       => 'TRX-ORG-B',
            'customer_name'  => 'Customer B',
            'customer_email' => 'b@example.com',
            'customer_phone' => '08124',
            'total_price'    => 25000,
            'status'         => 'success',
        ]);
    }

    /**
     * Test: Organizer can only see their own events.
     */
    public function test_organizer_can_only_see_their_own_events()
    {
        $response = $this->actingAs($this->organizerA)->get(route('admin.events.index'));

        $response->assertStatus(200);
        $response->assertSee('Event Organizer A');
        $response->assertDontSee('Event Organizer B');
    }

    /**
     * Test: Organizer cannot access/edit another organizer's event details.
     */
    public function test_organizer_cannot_edit_other_organizers_events()
    {
        // Try edit form
        $response = $this->actingAs($this->organizerA)->get(route('admin.events.edit', $this->eventB->id));
        $response->assertStatus(403);

        // Try update request
        $response = $this->actingAs($this->organizerA)->patch(route('admin.events.update', $this->eventB->id), [
            'title' => 'Hijacked Event',
        ]);
        $response->assertStatus(403);
    }

    /**
     * Test: Organizer can only see their own transactions.
     */
    public function test_organizer_can_only_see_their_own_transactions()
    {
        $response = $this->actingAs($this->organizerA)->get(route('admin.transactions.index'));

        $response->assertStatus(200);
        $response->assertSee('TRX-ORG-A');
        $response->assertDontSee('TRX-ORG-B');
    }

    /**
     * Test: Superadmin can see all events and transactions.
     */
    public function test_superadmin_can_see_all_events_and_transactions()
    {
        // Check events index
        $response = $this->actingAs($this->superadmin)->get(route('admin.events.index'));
        $response->assertSee('Event Organizer A');
        $response->assertSee('Event Organizer B');

        // Check transactions index
        $response = $this->actingAs($this->superadmin)->get(route('admin.transactions.index'));
        $response->assertSee('TRX-ORG-A');
        $response->assertSee('TRX-ORG-B');
    }

    /**
     * Test: Dashboard statistics are scoped by tenant.
     */
    public function test_organizer_dashboard_scopes_statistics()
    {
        // Login as organizer A
        $response = $this->actingAs($this->organizerA)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('totalRevenue', 15000);
        $response->assertViewHas('ticketsSold', 1);
        $response->assertViewHas('activeEvents', 1);
        $response->assertViewHas('pendingOrders', 0);

        // Login as superadmin
        $response = $this->actingAs($this->superadmin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('totalRevenue', 40000); // 15000 + 25000
        $response->assertViewHas('ticketsSold', 2);
        $response->assertViewHas('activeEvents', 2);
    }

    /**
     * Test: Organizer cannot verify/check-in tickets belonging to another organizer's events.
     */
    public function test_organizer_cannot_verify_other_organizers_ticket()
    {
        // Try verifying other organizer's ticket
        $response = $this->actingAs($this->organizerA)
                         ->post(route('admin.checkin.verify'), ['order_id' => $this->transactionB->order_id]);
        
        $response->assertStatus(403);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Akses Ditolak! Anda tidak berwenang memvalidasi tiket event ini.',
        ]);

        // Verifying own ticket should succeed
        $response = $this->actingAs($this->organizerA)
                         ->post(route('admin.checkin.verify'), ['order_id' => $this->transactionA->order_id]);
        
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);
        $this->assertEquals('used', $this->transactionA->fresh()->status);
    }
}
