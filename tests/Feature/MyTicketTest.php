<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyTicketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Guest is redirected to login when trying to access /my-ticket.
     */
    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get(route('my-ticket'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Authenticated user can see /my-ticket page.
     */
    public function test_user_can_access_my_ticket_page()
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get(route('my-ticket'));
        $response->assertStatus(200);
        $response->assertViewIs('my-ticket');
    }

    /**
     * Test: My Ticket page displays successful transactions and hides pending/failed transactions.
     */
    public function test_my_ticket_page_displays_only_successful_transactions()
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        
        $event1 = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Event Berhasil',
        ]);
        
        $event2 = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Event Pending',
        ]);

        // Successful transaction for user
        Transaction::factory()->create([
            'event_id' => $event1->id,
            'customer_email' => $user->email,
            'status' => 'success',
            'order_id' => 'TRX-SUCCESS-1',
        ]);

        // Pending transaction for user
        Transaction::factory()->create([
            'event_id' => $event2->id,
            'customer_email' => $user->email,
            'status' => 'Pending',
            'order_id' => 'TRX-PENDING-2',
        ]);

        $response = $this->actingAs($user)->get(route('my-ticket'));
        $response->assertStatus(200);
        $response->assertSee('Event Berhasil');
        $response->assertSee('TRX-SUCCESS-1');
        $response->assertDontSee('Event Pending');
        $response->assertDontSee('TRX-PENDING-2');
    }

    /**
     * Test: Review button displays only for past events (ended >= 1 day ago) and when not yet reviewed.
     */
    public function test_review_button_visibility_rules()
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();

        // 1. Event ended 2 days ago (eligible)
        $pastEvent = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Past Event Not Reviewed',
            'date' => now()->subDays(2),
        ]);
        Transaction::factory()->create([
            'event_id' => $pastEvent->id,
            'customer_email' => $user->email,
            'status' => 'success',
        ]);

        // 2. Event ended 2 days ago but already reviewed (not eligible)
        $reviewedEvent = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Past Event Reviewed',
            'date' => now()->subDays(2),
        ]);
        Transaction::factory()->create([
            'event_id' => $reviewedEvent->id,
            'customer_email' => $user->email,
            'status' => 'success',
        ]);
        Review::create([
            'user_id' => $user->id,
            'event_id' => $reviewedEvent->id,
            'rating' => 5,
            'review_text' => 'Bagus!',
        ]);

        // 3. Event in the future (not eligible)
        $futureEvent = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Future Event',
            'date' => now()->addDays(5),
        ]);
        Transaction::factory()->create([
            'event_id' => $futureEvent->id,
            'customer_email' => $user->email,
            'status' => 'success',
        ]);

        $response = $this->actingAs($user)->get(route('my-ticket'));
        $response->assertStatus(200);
        
        $response->assertSee('Past Event Not Reviewed');
        $response->assertSee('Beri Ulasan');
        
        $response->assertSee('Past Event Reviewed');
        $response->assertSee('Ulasan Terkirim');
        
        $response->assertSee('Future Event');
    }
}
