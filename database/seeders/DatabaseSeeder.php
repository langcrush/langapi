<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\User;
use App\Models\Word;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();
        Category::factory(10)->create();

        $words = [];
        $categories_ids = collect(Category::all()->modelKeys());
        foreach ($categories_ids as $value) {
            for ($i = 0; $i < 50; $i++) {
                $words[] = [
                    'name'=> fake()->word(),
                    'translation'=> fake('uk_UA')->word(), // doesn't work, puts latin anyway
                    'category_id'=> $value,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            }
        }
        Word::insert($words);
    }
}
