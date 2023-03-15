<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Category;
use App\Models\Shelf;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::factory()->create([
             'name' => 'adminp',
             'email' => 'adminp@admin.com',
         ]);

         \App\Models\User::factory(10)->create();

         Category::factory(10)->create();

         Shelf::factory(10)->create();

         Book::factory(50)->create();

         Transaction::factory(20)->create();
    }
}
