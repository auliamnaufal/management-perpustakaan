<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->email(),
            'nisn' => fake()->randomNumber(9, true),
            'class' => fake()->randomElement(['1', '2', '3']),
            'pickup_date' => fake()->dateTime(),
            'return_date' => fake()->dateTime(),
            'book_id' => Book::all()->random()->id,
            'is_returned' => fake()->boolean()
        ];
    }
}
