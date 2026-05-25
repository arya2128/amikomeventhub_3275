<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'order_id' => 'ORDER-' . $this->faker->unique()->numerify('############'),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'customer_phone' => $this->faker->phoneNumber(),
            'total_price' => $this->faker->numberBetween(50000, 5000000),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'cancelled']),
            'snap_token' => null,
        ];
    }
}
