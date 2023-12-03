<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Provider>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Provider::class;
    public function definition(): array
    {
        return [
            'company_name' => fake()->company,
            'contact_name' => fake()->name,
            'address' => fake()->address,
            'phone1' => fake()->unique()->phoneNumber,
            'phone2' => fake()->unique()->phoneNumber,
            'email' => fake()->unique()->safeEmail,
            'category' => fake()->word,
            'segment' => fake()->word,
            // 'rating' => fake()->optional()->numberBetween(1, 5),
        ];
    }
}
