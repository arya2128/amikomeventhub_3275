<?php

namespace Tests\Feature\Admin;

use App\Models\Transaction;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Display all transactions (INDEX)
     */
    public function test_index_displays_all_transactions()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transactions = Transaction::factory()->count(3)->create(['event_id' => $event->id]);

        $response = $this->get(route('admin.transactions.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.transactions.index');
        $response->assertViewHas('transactions');
        $response->assertViewHas('stats');
    }

    /**
     * Test: Search transactions by order_id
     */
    public function test_index_search_by_order_id()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        Transaction::factory()->create(['event_id' => $event->id, 'order_id' => 'ORDER-001']);
        Transaction::factory()->create(['event_id' => $event->id, 'order_id' => 'ORDER-002']);

        $response = $this->get(route('admin.transactions.index', ['search' => 'ORDER-001']));

        $response->assertStatus(200);
    }

    /**
     * Test: Filter transactions by status
     */
    public function test_index_filter_by_status()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        Transaction::factory()->create(['event_id' => $event->id, 'status' => 'pending']);
        Transaction::factory()->create(['event_id' => $event->id, 'status' => 'completed']);

        $response = $this->get(route('admin.transactions.index', ['status' => 'completed']));

        $response->assertStatus(200);
    }

    /**
     * Test: Show transaction details (SHOW)
     */
    public function test_show_displays_transaction_details()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transaction = Transaction::factory()->create(['event_id' => $event->id]);

        $response = $this->get(route('admin.transactions.show', $transaction->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.transactions.show');
        $response->assertViewHas('transaction', $transaction);
    }

    /**
     * Test: Show edit transaction form
     */
    public function test_edit_shows_form()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transaction = Transaction::factory()->create(['event_id' => $event->id]);

        $response = $this->get(route('admin.transactions.edit', $transaction->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.transactions.edit');
        $response->assertViewHas('transaction', $transaction);
        $response->assertViewHas('statuses');
    }

    /**
     * Test: Update transaction status (UPDATE)
     */
    public function test_update_transaction_status()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transaction = Transaction::factory()->create([
            'event_id' => $event->id,
            'status' => 'pending'
        ]);

        $response = $this->patch(route('admin.transactions.update', $transaction->id), [
            'status' => 'completed',
        ]);

        $response->assertRedirect(route('admin.transactions.show', $transaction->id));
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'completed',
        ]);
    }

    /**
     * Test: Update validates status enum
     */
    public function test_update_validates_status()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transaction = Transaction::factory()->create(['event_id' => $event->id]);

        $response = $this->patch(route('admin.transactions.update', $transaction->id), [
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
    }

    /**
     * Test: Update status from pending to failed
     */
    public function test_update_status_pending_to_failed()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transaction = Transaction::factory()->create([
            'event_id' => $event->id,
            'status' => 'pending'
        ]);

        $response = $this->patch(route('admin.transactions.update', $transaction->id), [
            'status' => 'failed',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'failed',
        ]);
    }

    /**
     * Test: Delete transaction (DESTROY)
     */
    public function test_destroy_deletes_transaction()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transaction = Transaction::factory()->create(['event_id' => $event->id]);

        $response = $this->delete(route('admin.transactions.destroy', $transaction->id));

        $response->assertRedirect(route('admin.transactions.index'));
        $this->assertDatabaseMissing('transactions', [
            'id' => $transaction->id,
        ]);
    }

    /**
     * Test: Delete non-existent transaction returns 404
     */
    public function test_destroy_nonexistent_returns_404()
    {
        $response = $this->delete(route('admin.transactions.destroy', 999));

        $response->assertStatus(404);
    }

    /**
     * Test: Bulk update multiple transactions
     */
    public function test_bulk_update_transactions()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create(['category_id' => $category->id]);
        $transaction1 = Transaction::factory()->create(['event_id' => $event->id, 'status' => 'pending']);
        $transaction2 = Transaction::factory()->create(['event_id' => $event->id, 'status' => 'pending']);
        $transaction3 = Transaction::factory()->create(['event_id' => $event->id, 'status' => 'pending']);

        $response = $this->post(route('admin.transactions.bulk-update'), [
            'transaction_ids' => [$transaction1->id, $transaction2->id],
            'status' => 'completed',
        ]);

        $response->assertRedirect(route('admin.transactions.index'));
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction1->id,
            'status' => 'completed',
        ]);
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction2->id,
            'status' => 'completed',
        ]);
        // Transaction 3 should remain pending
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction3->id,
            'status' => 'pending',
        ]);
    }
}

