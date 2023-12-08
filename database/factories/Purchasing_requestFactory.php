<?php

namespace Database\Factories;

use App\Models\Purchasing_request;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Purchasing_requestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Purchasing_request::class;
    public function definition(): array
    {
        return [
            'project' => fake()->sentence,
            'title' => fake()->sentence,
            'product_description' => fake()->paragraph,
            'due_date' => fake()->date(),
        ];
    }
}
