<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Word>
 */
class WordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $words = explode(' ',"гей хлопці не вспію на ґанку ваша файна їжа знищується бурундучком");
        return [
            'name'=>fake()->word(),
            // 'translation'=>array_rand(array_flip($words), 1),
            'translation'=>fake('uk_UA')->word(),
            'category_id'=>fake()->numberBetween(1, 10)
        ];
    }
}
