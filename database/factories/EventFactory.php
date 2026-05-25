<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'date' => $this->faker->dateTimeBetween('+1 days', '+1 year'),
            'location' => $this->faker->city(),
            'price' => $this->faker->numberBetween(50000, 1000000),
            'stock' => $this->faker->numberBetween(10, 500),
            'poster_path' => null,
        ];
    }
}
