<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stats>
 */
class StatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=>fake()->numberBetween(1, 5),
            'category_id'=>fake()->numberBetween(1, 10),
            'score'=>fake()->numberBetween(0, 5000)
        ];
    }
}
