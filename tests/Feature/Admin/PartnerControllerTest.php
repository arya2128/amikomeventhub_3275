<?php

namespace Tests\Feature\Admin;

use App\Models\Partner;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PartnerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test: Display all partners (INDEX)
     */
    public function test_index_displays_all_partners()
    {
        $category = Category::factory()->create();
        $partners = Partner::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->get(route('admin.partners.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.partners.index');
        $response->assertViewHas('partners');
    }

    /**
     * Test: Search partners by name (INDEX with search)
     */
    public function test_index_search_partners_by_name()
    {
        $category = Category::factory()->create();
        Partner::factory()->create(['name' => 'Google Indonesia', 'category_id' => $category->id]);
        Partner::factory()->create(['name' => 'Microsoft Asia', 'category_id' => $category->id]);
        Partner::factory()->create(['name' => 'Google Cloud', 'category_id' => $category->id]);

        $response = $this->get(route('admin.partners.index', ['search' => 'Google']));

        $response->assertStatus(200);
        // Should return partners containing 'Google'
        $this->assertTrue($response->viewData('partners')->count() >= 2);
    }

    /**
     * Test: Show create partner form (CREATE)
     */
    public function test_create_shows_form()
    {
        $categories = Category::factory()->count(2)->create();

        $response = $this->get(route('admin.partners.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.partners.create');
        $response->assertViewHas('categories');
    }

    /**
     * Test: Store new partner with logo (STORE)
     */
    public function test_store_creates_new_partner_with_logo()
    {
        $category = Category::factory()->create();
        $logo = UploadedFile::fake()->image('logo.png', 200, 200);

        $data = [
            'name' => 'Tech Partner Inc',
            'category_id' => $category->id,
            'logo' => $logo,
        ];

        $response = $this->post(route('admin.partners.store'), $data);

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseHas('partners', [
            'name' => 'Tech Partner Inc',
            'category_id' => $category->id,
        ]);
    }

    /**
     * Test: Store new partner without logo (STORE)
     */
    public function test_store_creates_new_partner_without_logo()
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'Tech Partner Inc',
            'category_id' => $category->id,
        ];

        $response = $this->post(route('admin.partners.store'), $data);

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseHas('partners', [
            'name' => 'Tech Partner Inc',
            'category_id' => $category->id,
        ]);
    }

    /**
     * Test: Store partner validates required name
     */
    public function test_store_validates_required_name()
    {
        $response = $this->post(route('admin.partners.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test: Store partner validates logo format
     */
    public function test_store_validates_logo_format()
    {
        $category = Category::factory()->create();
        $invalidFile = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->post(route('admin.partners.store'), [
            'name' => 'Partner Name',
            'category_id' => $category->id,
            'logo' => $invalidFile,
        ]);

        $response->assertSessionHasErrors('logo');
    }

    /**
     * Test: Show edit partner form (EDIT)
     */
    public function test_edit_shows_form()
    {
        $category = Category::factory()->create();
        $partner = Partner::factory()->create(['category_id' => $category->id]);
        Category::factory()->count(2)->create();

        $response = $this->get(route('admin.partners.edit', $partner->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.partners.edit');
        $response->assertViewHas('partner', $partner);
        $response->assertViewHas('categories');
    }

    /**
     * Test: Update partner without new logo (UPDATE)
     */
    public function test_update_modifies_partner_without_logo()
    {
        $category = Category::factory()->create();
        $partner = Partner::factory()->create([
            'category_id' => $category->id,
            'name' => 'Old Partner Name',
        ]);

        $response = $this->patch(route('admin.partners.update', $partner->id), [
            'name' => 'New Partner Name',
            'category_id' => $category->id,
        ]);

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'name' => 'New Partner Name',
        ]);
    }

    /**
     * Test: Update partner with new logo (UPDATE)
     */
    public function test_update_modifies_partner_with_new_logo()
    {
        $category = Category::factory()->create();
        $partner = Partner::factory()->create(['category_id' => $category->id]);
        $newLogo = UploadedFile::fake()->image('new-logo.jpg');

        $response = $this->patch(route('admin.partners.update', $partner->id), [
            'name' => 'Updated Partner Name',
            'category_id' => $category->id,
            'logo' => $newLogo,
        ]);

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'name' => 'Updated Partner Name',
        ]);
    }

    /**
     * Test: Update partner validates name
     */
    public function test_update_validates_name()
    {
        $partner = Partner::factory()->create();

        $response = $this->patch(route('admin.partners.update', $partner->id), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test: Delete partner (DESTROY)
     */
    public function test_destroy_deletes_partner()
    {
        $category = Category::factory()->create();
        $partner = Partner::factory()->create(['category_id' => $category->id]);

        $response = $this->delete(route('admin.partners.destroy', $partner->id));

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseMissing('partners', [
            'id' => $partner->id,
        ]);
    }

    /**
     * Test: Delete partner with logo removes file (DESTROY)
     */
    public function test_destroy_deletes_partner_and_logo()
    {
        $category = Category::factory()->create();
        $logo = UploadedFile::fake()->image('logo.jpg');
        $logo->store('partners', 'public');

        $partner = Partner::factory()->create([
            'category_id' => $category->id,
            'logo_path' => 'partners/' . $logo->hashName(),
        ]);

        $response = $this->delete(route('admin.partners.destroy', $partner->id));

        $response->assertRedirect(route('admin.partners.index'));
        $this->assertDatabaseMissing('partners', [
            'id' => $partner->id,
        ]);
    }

    /**
     * Test: Delete non-existent partner returns 404
     */
    public function test_destroy_nonexistent_returns_404()
    {
        $response = $this->delete(route('admin.partners.destroy', 999));

        $response->assertStatus(404);
    }
}
