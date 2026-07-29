<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = \App\Models\User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin);
    }

    /**
     * Test: Display all categories (INDEX)
     */
    public function test_index_displays_all_categories()
    {
        $categories = Category::factory()->count(3)->create();

        $response = $this->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
        $response->assertViewHas('categories');
    }

    /**
     * Test: Search categories by name (INDEX with search)
     */
    public function test_index_search_categories_by_name()
    {
        Category::factory()->create(['name' => 'Technology']);
        Category::factory()->create(['name' => 'Sports']);
        Category::factory()->create(['name' => 'Tech Meetup']);

        $response = $this->get(route('admin.categories.index', ['search' => 'Tech']));

        $response->assertStatus(200);
        // Should return categories containing 'Tech'
        $this->assertTrue($response->viewData('categories')->count() >= 2);
    }

    /**
     * Test: Show create category form (CREATE)
     */
    public function test_create_shows_form()
    {
        $response = $this->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.create');
    }

    /**
     * Test: Store new category (STORE)
     */
    public function test_store_creates_new_category()
    {
        $data = [
            'name' => 'Technology Events',
        ];

        $response = $this->post(route('admin.categories.store'), $data);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Technology Events',
            'slug' => 'technology-events',
        ]);
    }

    /**
     * Test: Store category with validation error
     */
    public function test_store_validates_required_name()
    {
        $response = $this->post(route('admin.categories.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test: Store category with duplicate name
     */
    public function test_store_validates_unique_name()
    {
        Category::factory()->create(['name' => 'Technology']);

        $response = $this->post(route('admin.categories.store'), [
            'name' => 'Technology',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test: Show edit category form (EDIT)
     */
    public function test_edit_shows_form()
    {
        $category = Category::factory()->create();

        $response = $this->get(route('admin.categories.edit', $category->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas('category', $category);
    }

    /**
     * Test: Update category (UPDATE)
     */
    public function test_update_modifies_category()
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->patch(route('admin.categories.update', $category->id), [
            'name' => 'New Name',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
            'slug' => 'new-name',
        ]);
    }

    /**
     * Test: Update category with validation error
     */
    public function test_update_validates_name()
    {
        $category = Category::factory()->create();

        $response = $this->patch(route('admin.categories.update', $category->id), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test: Delete category (DESTROY)
     */
    public function test_destroy_deletes_category()
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('admin.categories.destroy', $category->id));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * Test: Delete non-existent category returns 404
     */
    public function test_destroy_nonexistent_returns_404()
    {
        $response = $this->delete(route('admin.categories.destroy', 999));

        $response->assertStatus(404);
    }
}
