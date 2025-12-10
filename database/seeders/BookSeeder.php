<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        \App\Models\Book::create(['title' => 'Belajar Laravel 11', 'code' => 'B001']);
        \App\Models\Book::create(['title' => 'Mastering Tailwind', 'code' => 'B002']);
        \App\Models\Book::create(['title' => 'Filosofi Teras', 'code' => 'B003']);
        \App\Models\Book::create(['title' => 'Atomic Habits', 'code' => 'B004']);
        \App\Models\Book::create(['title' => 'Harry Potter', 'code' => 'B005']);
    }
}
