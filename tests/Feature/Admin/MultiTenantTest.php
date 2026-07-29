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
}
