<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test: Display all events (INDEX)
     */
    public function test_index_displays_all_events()
    {
        $category = Category::factory()->create();
        $events = Event::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->get(route('admin.events.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.events.index');
        $response->assertViewHas('events');
    }

    /**
     * Test: Show create event form (CREATE)
     */
    public function test_create_shows_form()
    {
        $categories = Category::factory()->count(2)->create();

        $response = $this->get(route('admin.events.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.events.create');
        $response->assertViewHas('categories');
    }

    /**
     * Test: Store new event with poster (STORE)
     */
    public function test_store_creates_new_event()
    {
        $category = Category::factory()->create();
        $poster = UploadedFile::fake()->image('poster.jpg', 600, 400);

        $data = [
            'title' => 'Tech Conference 2026',
            'category_id' => $category->id,
            'description' => 'A great tech conference',
            'date' => '2026-06-15 10:00:00',
            'location' => 'Jakarta Convention Center',
            'price' => 500000,
            'stock' => 100,
            'poster' => $poster,
        ];

        $response = $this->post(route('admin.events.store'), $data);

        $response->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseHas('events', [
            'title' => 'Tech Conference 2026',
            'category_id' => $category->id,
            'price' => 500000,
            'stock' => 100,
        ]);
    }

    /**
     * Test: Store event validates required fields
     */
    public function test_store_validates_required_fields()
    {
        $response = $this->post(route('admin.events.store'), [
            'title' => '',
            'category_id' => '',
            'date' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'category_id', 'date']);
    }

    /**
     * Test: Store event validates poster image
     */
    public function test_store_validates_poster_format()
    {
        $category = Category::factory()->create();
        $invalidFile = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->post(route('admin.events.store'), [
            'title' => 'Event Title',
            'category_id' => $category->id,
            'description' => 'Description',
            'date' => '2026-06-15',
            'location' => 'Location',
            'price' => 100000,
            'stock' => 50,
            'poster' => $invalidFile,
        ]);

        $response->assertSessionHasErrors('poster');
    }

    /**
     * Test: Show edit event form (EDIT)
     */
    public function test_edit_shows_form()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        Category::factory()->count(2)->create();

        $response = $this->get(route('admin.events.edit', $event->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.events.edit');
        $response->assertViewHas('event', $event);
        $response->assertViewHas('categories');
    }

    /**
     * Test: Update event without new poster (UPDATE)
     */
    public function test_update_modifies_event_without_poster()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create([
            'category_id' => $category->id,
            'title' => 'Old Title',
        ]);

        $response = $this->patch(route('admin.events.update', $event->id), [
            'title' => 'New Title',
            'category_id' => $category->id,
            'description' => 'Updated description',
            'date' => '2026-07-20',
            'location' => 'New Location',
            'price' => 600000,
            'stock' => 80,
        ]);

        $response->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'New Title',
            'location' => 'New Location',
        ]);
    }

    /**
     * Test: Update event with new poster (UPDATE)
     */
    public function test_update_modifies_event_with_new_poster()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $newPoster = UploadedFile::fake()->image('new-poster.jpg');

        $response = $this->patch(route('admin.events.update', $event->id), [
            'title' => 'Updated Title',
            'category_id' => $category->id,
            'description' => 'Updated',
            'date' => '2026-07-20',
            'location' => 'New Location',
            'price' => 700000,
            'stock' => 60,
            'poster' => $newPoster,
        ]);

        $response->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Title',
        ]);
    }

    /**
     * Test: Delete event (DESTROY)
     */
    public function test_destroy_deletes_event()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);

        $response = $this->delete(route('admin.events.destroy', $event->id));

        $response->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }

    /**
     * Test: Delete event with poster removes file (DESTROY)
     */
    public function test_destroy_deletes_event_and_poster()
    {
        $category = Category::factory()->create();
        $poster = UploadedFile::fake()->image('poster.jpg');
        $poster->store('posters', 'public');

        $event = Event::factory()->create([
            'category_id' => $category->id,
            'poster_path' => 'posters/' . $poster->hashName(),
        ]);

        $response = $this->delete(route('admin.events.destroy', $event->id));

        $response->assertRedirect(route('admin.events.index'));
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }

    /**
     * Test: Delete non-existent event returns 404
     */
    public function test_destroy_nonexistent_returns_404()
    {
        $response = $this->delete(route('admin.events.destroy', 999));

        $response->assertStatus(404);
    }
}
