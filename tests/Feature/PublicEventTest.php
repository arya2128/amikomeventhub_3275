<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Katalog page works and displays events.
     */
    public function test_katalog_page_displays_events(): void
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Concert Tour 2026',
        ]);

        $response = $this->get(route('katalog'));

        $response->assertStatus(200);
        $response->assertSee('Concert Tour 2026');
    }

    /**
     * Test: Katalog search filters events.
     */
    public function test_katalog_search_filters_events(): void
    {
        $category = Category::factory()->create();
        $event1 = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Rock Fest 2026',
        ]);
        $event2 = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Classical Concert',
        ]);

        // Search for Rock
        $response = $this->get(route('katalog', ['search' => 'Rock']));
        $response->assertStatus(200);
        $response->assertSee('Rock Fest 2026');
        $response->assertDontSee('Classical Concert');
    }

    /**
     * Test: Event detail page works and displays dynamic details.
     */
    public function test_event_detail_page_displays_details(): void
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Tech Workshop',
            'location' => 'Amikom Cinema',
            'price' => 75000,
        ]);

        $response = $this->get(route('event.show', $event->id));

        $response->assertStatus(200);
        $response->assertSee('Tech Workshop');
        $response->assertSee('Amikom Cinema');
        $response->assertSee('75.000');
    }

    /**
     * Test: Checkout page works and shows order summary.
     */
    public function test_checkout_page_displays_order_summary(): void
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Design Sprint',
        ]);

        $response = $this->get(route('checkout', $event->id));

        $response->assertStatus(200);
        $response->assertSee('Design Sprint');
    }

    /**
     * Test: Submitting checkout form creates a transaction, decrements stock, and redirects.
     */
    public function test_checkout_form_submission_creates_transaction(): void
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create([
            'category_id' => $category->id,
            'stock' => 10,
            'price' => 50000,
        ]);

        $response = $this->post(route('checkout.store', $event->id), [
            'customer_name' => 'John Doe',
            'customer_email' => 'johndoe@example.com',
            'customer_phone' => '08123456789',
        ]);

        // Assert redirect to ticket view
        $transaction = Transaction::first();
        $this->assertNotNull($transaction);
        $response->assertRedirect(route('ticket.show', $transaction->order_id));

        // Assert database state
        $this->assertDatabaseHas('transactions', [
            'customer_name' => 'John Doe',
            'customer_email' => 'johndoe@example.com',
            'customer_phone' => '08123456789',
            'status' => 'completed',
            'total_price' => 55000, // 50000 price + 5000 service fee
        ]);

        // Assert stock decrement
        $event->refresh();
        $this->assertEquals(9, $event->stock);
    }

    /**
     * Test: Submitting checkout form fails when stock is 0.
     */
    public function test_checkout_form_fails_when_no_stock(): void
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create([
            'category_id' => $category->id,
            'stock' => 0,
        ]);

        $response = $this->post(route('checkout.store', $event->id), [
            'customer_name' => 'John Doe',
            'customer_email' => 'johndoe@example.com',
            'customer_phone' => '08123456789',
        ]);

        $response->assertSessionHasErrors('stock');
        $this->assertEquals(0, Transaction::count());
    }

    /**
     * Test: Ticket page displays correct ticket details.
     */
    public function test_ticket_page_displays_details(): void
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transaction = Transaction::factory()->create([
            'event_id' => $event->id,
            'order_id' => 'TRX-TEST1234',
            'customer_name' => 'Alice Smith',
        ]);

        $response = $this->get(route('ticket.show', $transaction->order_id));

        $response->assertStatus(200);
        $response->assertSee('Alice Smith');
        $response->assertSee('TRX-TEST1234');
    }
}
