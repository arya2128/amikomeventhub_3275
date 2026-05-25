<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'category_id' => Category::factory(),
            'logo_path' => null,
        ];
    }
}
