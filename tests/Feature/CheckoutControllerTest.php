<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Checkout page loads successfully with event.
     */
    public function test_checkout_page_loads_with_event_details()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create([
            'category_id' => $category->id,
            'stock'       => 10,
        ]);

        $response = $this->get(route('checkout.create', $event->id));

        $response->assertStatus(200);
        $response->assertViewIs('checkout.create');
        $response->assertViewHas('event');
    }

    /**
     * Test: Guest can checkout successfully.
     */
    public function test_guest_can_checkout_successfully()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create([
            'category_id' => $category->id,
            'stock'       => 10,
            'price'       => 100000,
        ]);

        $response = $this->post(route('checkout.store', $event->id), [
            'customer_name'  => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '08123456789',
        ]);

        $transaction = Transaction::first();
        $this->assertNotNull($transaction);
        $response->assertRedirect(route('checkout.payment', $transaction->order_id));

        $this->assertDatabaseHas('transactions', [
            'customer_name'  => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '08123456789',
            'total_price'    => 105000, // 100k tiket + 5k biaya layanan
            'status'         => 'Pending',
        ]);
    }

    /**
     * Test: Guest cannot checkout if ticket is sold out.
     */
    public function test_guest_cannot_checkout_if_ticket_is_sold_out()
    {
        $category = Category::factory()->create();
        $event = Event::factory()->create([
            'category_id' => $category->id,
            'stock'       => 0,
        ]);

        $response = $this->post(route('checkout.store', $event->id), [
            'customer_name'  => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '08123456789',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('transactions', 0);
    }

    /**
     * Test: Guest can checkout free event successfully and bypass Midtrans payment.
     */
    public function test_guest_can_checkout_free_event_successfully_and_bypass_midtrans()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $category = Category::factory()->create();
        $event = Event::factory()->create([
            'category_id' => $category->id,
            'stock'       => 5,
            'price'       => 0, // Free event
        ]);

        $response = $this->post(route('checkout.store', $event->id), [
            'customer_name'  => 'Free Ticket Buyer',
            'customer_email' => 'free@example.com',
            'customer_phone' => '08123456780',
        ]);

        $transaction = Transaction::first();
        $this->assertNotNull($transaction);

        // Should redirect directly to success page (bypass payment page)
        $response->assertRedirect(route('checkout.success', $transaction->order_id));

        // Status should be set to success
        $this->assertDatabaseHas('transactions', [
            'customer_name'  => 'Free Ticket Buyer',
            'customer_email' => 'free@example.com',
            'customer_phone' => '08123456780',
            'total_price'    => 0,
            'status'         => 'success',
        ]);

        // Stock should be decremented immediately
        $this->assertEquals(4, $event->fresh()->stock);

        // E-Ticket Mail should be sent
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\EventTicketMail::class, function ($mail) use ($transaction) {
            return $mail->transaction->id === $transaction->id;
        });
    }
}
