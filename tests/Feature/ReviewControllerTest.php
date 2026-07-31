<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $event;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'user']);
        
        $category = Category::factory()->create();
        $this->event = Event::factory()->create([
            'category_id' => $category->id,
            'date'        => now()->subDays(2), // Past event (ended >= 1 day ago)
        ]);
    }

    /**
     * Test: Guest cannot review an event.
     */
    public function test_guest_cannot_review_event()
    {
        $response = $this->post(route('event.reviews.store', $this->event->id), [
            'rating'      => 5,
            'review_text' => 'Bagus sekali!',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('reviews', 0);
    }

    /**
     * Test: Logged-in user can submit a review for a past event.
     */
    public function test_user_can_submit_review_for_past_event()
    {
        Transaction::factory()->create([
            'event_id' => $this->event->id,
            'customer_email' => $this->user->email,
            'status' => 'success',
        ]);

        $response = $this->actingAs($this->user)
                         ->post(route('event.reviews.store', $this->event->id), [
                             'rating'      => 5,
                             'review_text' => 'Konser yang luar biasa!',
                         ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id'     => $this->user->id,
            'event_id'    => $this->event->id,
            'rating'      => 5,
            'review_text' => 'Konser yang luar biasa!',
        ]);
    }

    /**
     * Test: User cannot review a future event.
     */
    public function test_user_cannot_review_future_event()
    {
        $category = Category::factory()->create();
        $futureEvent = Event::factory()->create([
            'category_id' => $category->id,
            'date'        => now()->addDays(5), // Future event
        ]);

        Transaction::factory()->create([
            'event_id' => $futureEvent->id,
            'customer_email' => $this->user->email,
            'status' => 'success',
        ]);

        $response = $this->actingAs($this->user)
                         ->post(route('event.reviews.store', $futureEvent->id), [
                             'rating'      => 4,
                             'review_text' => 'Semoga seru!',
                         ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('reviews', 0);
    }

    /**
     * Test: Validate rating is required and within 1-5.
     */
    public function test_validation_requires_rating_between_1_and_5()
    {
        Transaction::factory()->create([
            'event_id' => $this->event->id,
            'customer_email' => $this->user->email,
            'status' => 'success',
        ]);

        $response = $this->actingAs($this->user)
                         ->post(route('event.reviews.store', $this->event->id), [
                             'rating'      => 6, // Invalid rating
                             'review_text' => 'Terlalu tinggi',
                         ]);

        $response->assertSessionHasErrors('rating');
    }

    /**
     * Test: User cannot review event without a successful transaction.
     */
    public function test_user_cannot_review_event_without_successful_transaction()
    {
        $response = $this->actingAs($this->user)
                         ->post(route('event.reviews.store', $this->event->id), [
                             'rating'      => 5,
                             'review_text' => 'Konser yang luar biasa!',
                         ]);

        $response->assertSessionHas('error', 'Anda harus memiliki transaksi yang berhasil untuk event ini sebelum memberikan ulasan.');
        $this->assertDatabaseCount('reviews', 0);
    }

    /**
     * Test: User cannot review event that ended less than one day ago.
     */
    public function test_user_cannot_review_event_that_ended_less_than_one_day_ago()
    {
        $category = Category::factory()->create();
        $recentEvent = Event::factory()->create([
            'category_id' => $category->id,
            'date'        => now()->subHours(12), // Ended 12 hours ago (less than 24 hours/1 day)
        ]);

        Transaction::factory()->create([
            'event_id' => $recentEvent->id,
            'customer_email' => $this->user->email,
            'status' => 'success',
        ]);

        $response = $this->actingAs($this->user)
                         ->post(route('event.reviews.store', $recentEvent->id), [
                             'rating'      => 5,
                             'review_text' => 'Konser yang luar biasa!',
                         ]);

        $response->assertSessionHas('error', 'Anda hanya dapat memberikan ulasan minimal satu hari setelah event selesai diselenggarakan.');
        $this->assertDatabaseCount('reviews', 0);
    }

    /**
     * Test: User cannot review same event multiple times.
     */
    public function test_user_cannot_review_same_event_multiple_times()
    {
        Transaction::factory()->create([
            'event_id' => $this->event->id,
            'customer_email' => $this->user->email,
            'status' => 'success',
        ]);

        // Submit first review
        $this->actingAs($this->user)
             ->post(route('event.reviews.store', $this->event->id), [
                 'rating'      => 5,
                 'review_text' => 'First review',
             ]);

        // Submit second review
        $response = $this->actingAs($this->user)
                         ->post(route('event.reviews.store', $this->event->id), [
                             'rating'      => 4,
                             'review_text' => 'Second review',
                         ]);

        $response->assertSessionHas('error', 'Anda sudah memberikan ulasan untuk event ini.');
        $this->assertDatabaseCount('reviews', 1);
    }
}
