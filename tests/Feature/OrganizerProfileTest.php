<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizerProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $organizer;
    protected $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organizer = User::factory()->create([
            'role' => 'organizer',
            'name' => 'Amikom Organizer',
        ]);

        $category = Category::factory()->create();
        $this->event = Event::factory()->create([
            'category_id' => $category->id,
            'user_id'     => $this->organizer->id,
            'title'       => 'Amikom Fair 2026',
        ]);
    }

    /**
     * Test: Accessing organizer profile page displays correct organizer details and events.
     */
    public function test_organizer_profile_page_displays_details_and_events()
    {
        $response = $this->get(route('organizer.profile', $this->organizer->id));

        $response->assertStatus(200);
        $response->assertSee('Amikom Organizer');
        $response->assertSee('Amikom Fair 2026');
    }

    /**
     * Test: Organizer profile page displays all reviews and ratings.
     */
    public function test_organizer_profile_page_displays_reviews_and_average_rating()
    {
        $user1 = User::factory()->create(['name' => 'Buyer One']);
        $user2 = User::factory()->create(['name' => 'Buyer Two']);

        Review::create([
            'user_id'     => $user1->id,
            'event_id'    => $this->event->id,
            'rating'      => 5,
            'review_text' => 'Event sangat menarik!',
        ]);

        Review::create([
            'user_id'     => $user2->id,
            'event_id'    => $this->event->id,
            'rating'      => 4,
            'review_text' => 'Keren sekali!',
        ]);

        $response = $this->get(route('organizer.profile', $this->organizer->id));

        $response->assertStatus(200);
        $response->assertSee('4.5'); // Average of 5 and 4
        $response->assertSee('2 ulasan tuntas');
        $response->assertSee('Buyer One');
        $response->assertSee('Event sangat menarik!');
        $response->assertSee('Buyer Two');
        $response->assertSee('Keren sekali!');
    }

    /**
     * Test: Organizer profile page aborts with 404 if user is not organizer or admin.
     */
    public function test_organizer_profile_page_fails_for_normal_user()
    {
        $normalUser = User::factory()->create(['role' => 'user']);

        $response = $this->get(route('organizer.profile', $normalUser->id));

        $response->assertStatus(404);
    }
}
