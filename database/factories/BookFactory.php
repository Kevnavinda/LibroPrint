<?php

namespace Database\Factories;

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
            'title' => $this->faker->sentence(3),
            'code' => $this->faker->unique()->bothify('BK-####'), 
            'author' => $this->faker->name(),
            'stock' => $this->faker->numberBetween(1, 15), 
            'description' => $this->faker->paragraph(),
        ];
    }
}
