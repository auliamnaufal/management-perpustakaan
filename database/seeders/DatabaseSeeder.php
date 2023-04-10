<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Category;
use App\Models\Shelf;
use App\Models\Student;
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

         Student::factory(5)->create();

         Category::factory(10)->create();

         Shelf::factory(10)->create();

         Book::factory(10)->create();

         Transaction::factory(15)->create();
    }
}
