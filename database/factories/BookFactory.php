<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Shelf;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => fake()->numberBetween(1000000000, 9999999999999),
            'title' => fake()->name(),
            'cover' => fake()->imageUrl(),
            'author' => fake()->name(),
            'shelf_id' => Shelf::all()->random()->id,
            'category_id' => Category::all()->random()->id,
            'stock' => fake()->randomDigit()
        ];
    }
}
