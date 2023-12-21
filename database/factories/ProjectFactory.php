<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Project::class;
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'code' => strtoupper(fake()->unique()->randomLetter . fake()->unique()->randomNumber(3)),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'priority' => fake()->randomElement(['Alta', 'Media', 'Baja']),
            'description' => fake()->paragraph(3),
        ];
    }
}
