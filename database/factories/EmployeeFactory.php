<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employees>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Employee::class;
    public function definition(): array
    {
        return [
            'name' => fake()->firstName,
            'lastname' => fake()->lastName,
            'cropped_image' =>'http://192.168.1.78:8000/image/imagen_recortada_1699731709.jpg',
            'gender' => fake()->randomElement(['Masculino', 'Femenino']),
            'state_civil' => fake()->randomElement(['Soltero(a)', 'married', 'Divorciado(a)', 'widowed']),
            'birthdate' => fake()->date,
            'dni' => fake()->unique()->numberBetween(10000000, 99999999),
            'email' => fake()->unique()->safeEmail,
            'phone1' => fake()->unique()->numberBetween(100000000, 999999999),
            'phone2' => fake()->optional()->numberBetween(100000000, 999999999),
        ];
    }
}
